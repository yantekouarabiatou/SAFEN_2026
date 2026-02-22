#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# === On fait le minimum pour démarrer vite ===

echo "Création dossier logs (rapide)"
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log || true

echo "Lien storage (rapide)"
php artisan storage:link --force || true

echo "Permissions rapides"
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "Démarrage PHP-FPM en arrière-plan"
php-fpm -D

# On attend seulement 5 secondes (pas 20)
echo "Attente très courte PHP-FPM..."
sleep 5

echo "Démarrage Nginx"
exec nginx -g "daemon off;"