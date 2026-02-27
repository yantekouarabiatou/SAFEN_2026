FROM php:8.2-fpm
# rebuild-2026-02-27-v4

# ── Dépendances système ──────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    nginx curl zip unzip git netcat-openbsd \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libicu-dev libgmp-dev libpq-dev \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql mbstring exif \
        pcntl bcmath gd zip intl gmp \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ── Composer ─────────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Répertoire de travail ────────────────────────────────────────────────────────
WORKDIR /var/www/html

# ── Copie du code source ─────────────────────────────────────────────────────────
COPY . .

# ── Création des répertoires nécessaires ─────────────────────────────────────────
RUN mkdir -p storage/logs \
             storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache/data \
             bootstrap/cache

# ── .env BUILD ONLY ───────────────────────────────────────────────────────────────
RUN printf 'APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=pgsql\nDB_HOST=127.0.0.1\nDB_PORT=5432\nDB_DATABASE=laravel\nDB_USERNAME=laravel\nDB_PASSWORD=\nCACHE_STORE=array\nSESSION_DRIVER=array\nQUEUE_CONNECTION=sync\n' > .env

# ── Supprimer post-autoload-dump pour éviter connexion BDD au build ───────────────
COPY scripts/remove_post_autoload.php /tmp/remove_post_autoload.php
RUN php /tmp/remove_post_autoload.php

# ── Installation Composer sans scripts BDD ───────────────────────────────────────
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# ── Lancer package:discover manuellement (sans BDD) ──────────────────────────────
RUN php artisan package:discover --ansi || true

# ── Génération clé ────────────────────────────────────────────────────────────────
RUN php artisan key:generate --force

# ── Permissions ──────────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage/framework/cache/data

# ── Configuration PHP-FPM ─────────────────────────────────────────────────────────
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i '/listen.allowed_clients/d' /usr/local/etc/php-fpm.d/www.conf || true

# ── Script de démarrage ──────────────────────────────────────────────────────────
RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

# ── Configuration Nginx ──────────────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/sites-enabled/default

# ── Port exposé ──────────────────────────────────────────────────────────────────
EXPOSE 80

# ── Démarrage ────────────────────────────────────────────────────────────────────
CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]