FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx curl zip unzip git netcat-openbsd \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libicu-dev libgmp-dev libpq-dev \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql mbstring exif \
        pcntl bcmath gd zip intl gmp \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p storage/logs \
             storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache/data \
             bootstrap/cache

# .env minimal pour le build
RUN cp .env.example .env || echo "APP_KEY=\nDB_CONNECTION=pgsql" > .env

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

RUN php artisan key:generate --force

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage/framework/cache/data

RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i '/listen.allowed_clients/d' /usr/local/etc/php-fpm.d/www.conf || true

RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

COPY docker/nginx.conf /etc/nginx/sites-enabled/default

EXPOSE 80

CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]
