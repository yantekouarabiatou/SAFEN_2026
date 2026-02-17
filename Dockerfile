FROM php:8.2-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copie des fichiers
COPY . .

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Config nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["/bin/sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'"]