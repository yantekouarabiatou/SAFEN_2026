#!/usr/bin/env bash
set -e  # s'arrête dès qu'une commande échoue

cd /var/www/html

echo "Vérification vendor/autoload.php..."
if [ ! -f vendor/autoload.php ]; then
    echo "ERREUR : vendor/autoload.php introuvable → composer install a échoué pendant le build !"
    ls -la vendor || true
    exit 1
fi

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache || echo "Route cache ignoré (peut échouer si pas de routes)"

echo "Running migrations..."
php artisan migrate --force || echo "Migration ignorée ou déjà à jour"

echo "Seeding database (optionnel)..."
php artisan db:seed --force || echo "Seed ignoré"

echo "Linking storage..."
php artisan storage:link

echo "Permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Démarrage des services..."
php-fpm -D
exec nginx -g "daemon off;"