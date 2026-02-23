#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

echo "==> Création des dossiers nécessaires"
mkdir -p storage/logs \
         storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         bootstrap/cache

echo "==> Création du fichier de log"
touch storage/logs/laravel.log

echo "==> Permissions storage & cache"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Cache config Laravel"
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "==> Migrations (fresh + seeds)"
php artisan migrate:fresh --seed --force

echo "==> Lien storage"
php artisan storage:link --force || true

echo "==> Démarrage PHP-FPM en arrière-plan"
php-fpm -D

echo "==> Attente PHP-FPM..."
sleep 3

echo "==> Démarrage Nginx"
exec nginx -g "daemon off;"
