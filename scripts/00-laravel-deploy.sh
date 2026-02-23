#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

echo "==> Clear des caches"
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "==> Migrations + Seeds"
php artisan migrate:fresh --seed --force

echo "==> Lien storage"
php artisan storage:link --force || true

echo "==> Démarrage PHP-FPM"
php-fpm -D

sleep 3

echo "==> Démarrage Nginx"
exec nginx -g "daemon off;"
