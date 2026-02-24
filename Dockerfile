FROM php:8.2-fpm

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

# ── .env minimal pour le build (sera écrasé au démarrage par les vars Render) ────
RUN cp .env.example .env 2>/dev/null || cat <<'EOF' > .env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=
EOF

# ── Installation des dépendances Composer ────────────────────────────────────────
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

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
