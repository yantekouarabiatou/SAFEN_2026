#!/usr/bin/env bash

cd /var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

echo "Linking storage..."
php artisan storage:link

echo "Starting php-fpm and nginx..."
php-fpm -D && nginx -g "daemon off;"