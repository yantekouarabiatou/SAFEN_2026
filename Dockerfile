FROM php:8.2-fpm
# rebuild-2026-02-27-v2

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

# ── .env BUILD ONLY : connexion BDD désactivée intentionnellement ────────────────
# Ce .env est écrasé au démarrage par 00-laravel-deploy.sh
RUN printf 'APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=pgsql\nDB_HOST=127.0.0.1\nDB_PORT=5432\nDB_DATABASE=laravel\nDB_USERNAME=laravel\nDB_PASSWORD=\nCACHE_STORE=array\nSESSION_DRIVER=array\nQUEUE_CONNECTION=sync\n' > .env

# ── Installation des dépendances Composer ────────────────────────────────────────
# DB_CONNECTION=array empêche Spatie de toucher la BDD pendant package:discover
RUN DB_CONNECTION=array \
    CACHE_STORE=array \
    SESSION_DRIVER=array \
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ── Génération d'une clé temporaire pour le build ────────────────────────────────
RUN php artisan key:generate --force

# ── Permissions ──────────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage/framework/cache/data

# ── Configuration PHP-FPM : écoute sur 127.0.0.1:9000 ───────────────────────────
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