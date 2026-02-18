FROM php:8.2-fpm

# Installation des paquets système et extensions PHP nécessaires
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
    libicu-dev \
    libgmp-dev \
    && docker-php-ext-install \
        pdo_mysql \
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

# Installation de netcat pour les tests de port (utile pour debug)
RUN apt-get update && apt-get install -y netcat-openbsd && apt-get clean

# Copie de Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie du code source
COPY . .

# Installation des dépendances Composer (en mode production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress --verbose

# Permissions (important pour Laravel)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Force PHP-FPM à écouter sur TCP 127.0.0.1:9000 (très important pour éviter les problèmes de socket)
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf \
    && echo "listen.allowed_clients = any" >> /usr/local/etc/php-fpm.d/www.conf

# Rend le script de déploiement exécutable
RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

# Copie de la config Nginx (doit exister dans ton repo)
COPY docker/nginx.conf /etc/nginx/sites-enabled/default

# Expose le port 80 (important pour Render)
EXPOSE 80

# Lance le script de démarrage
CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]