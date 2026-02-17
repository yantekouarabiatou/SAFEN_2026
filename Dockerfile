# ===============================
# 1) Image PHP officielle
# ===============================
FROM php:8.2-fpm

# ===============================
# 2) Installer dépendances système
# ===============================
RUN apt-get update && apt-get install -y \
    git curl unzip zip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev nginx \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# ===============================
# 3) Installer Composer
# ===============================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===============================
# 4) Définir dossier de travail
# ===============================
WORKDIR /var/www/html

# ===============================
# 5) Copier tout le projet Laravel
# ===============================
COPY . .

# ===============================
# 6) Installer dépendances Laravel
# ===============================
RUN composer install --no-dev --optimize-autoloader

# ===============================
# 7) Donner permissions storage
# ===============================
RUN chmod -R 775 storage bootstrap/cache

# ===============================
# 8) Créer lien storage/public
# ===============================
RUN php artisan storage:link || true

# ===============================
# 9) Copier config Nginx
# ===============================
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# ===============================
# 10) Exposer port Render
# ===============================
EXPOSE 80

# ===============================
# 11) Lancer Nginx + PHP-FPM
# ===============================
CMD service nginx start && php-fpm
