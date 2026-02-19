#!/bin/bash

echo "🚀 Lancement Laravel..."

# Cache config
php artisan config:clear
php artisan cache:clear

# Migrer + Seeder
php artisan migrate --force --seed

# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage public
php artisan storage:link

echo "✅ Application prête !"

# Démarrer PHP-FPM
php-fpm
