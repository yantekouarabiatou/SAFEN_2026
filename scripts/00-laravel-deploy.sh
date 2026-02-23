#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

echo "Création du fichier SQLite si inexistant"
mkdir -p database
touch database/database.sqlite

echo "Création dossier logs"
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log || true

echo "Migrations + Seeds"
php artisan migrate --seed --force

echo "Lien storage"
php artisan storage:link --force || true

echo "Permissions"
chown -R www-data:www-data storage bootstrap/cache database || true
chmod -R 775 storage bootstrap/cache || true
chmod 664 database/database.sqlite || true

echo "Démarrage PHP-FPM"
php-fpm -D

echo "Attente PHP-FPM..."
sleep 5

echo "Démarrage Nginx"
exec nginx -g "daemon off;"
