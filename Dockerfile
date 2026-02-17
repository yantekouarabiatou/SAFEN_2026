FROM richarvey/nginx-php-fpm:1.7.2

# Copie des fichiers dans le bon dossier
COPY . /var/www/html

# Image config
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Rendre le script exécutable
RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh

CMD ["/var/www/html/scripts/00-laravel-deploy.sh"]