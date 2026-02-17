FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Composer install en root AVANT tout le reste
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress --no-suggest
RUN composer diagnose
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

COPY docker/nginx.conf /etc/nginx/sites-enabled/default

EXPOSE 80

CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]