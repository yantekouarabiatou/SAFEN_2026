FROM php:8.2-fpm

# Installation des paquets système et extensions PHP
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    netcat-openbsd \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libgmp-dev \
    libpq-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        gmp \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code source
COPY . .

# Installation des dépendances Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuration PHP-FPM pour écouter sur 127.0.0.1:9000
RUN sed -i 's|;listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i '/listen.allowed_clients/d' /usr/local/etc/php-fpm.d/www.conf || true

# Script de démarrage exécutable
RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

# Configuration Nginx
COPY docker/nginx.conf /etc/nginx/sites-enabled/default

# Exposition du port
EXPOSE 80

# Démarrage
CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]