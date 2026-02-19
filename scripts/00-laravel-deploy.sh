#!/usr/bin/env bash
set -euo pipefail   # Arrêt sur erreur + variables non définies + pipefail

cd /var/www/html || { echo "ERREUR : impossible de cd /var/www/html"; exit 1; }

echo "Vérification vendor/autoload.php..."
if [ ! -f vendor/autoload.php ]; then
    echo "ERREUR CRITIQUE : vendor/autoload.php introuvable → composer install a échoué pendant le build !"
    ls -la vendor 2>/dev/null || echo "Dossier vendor n'existe pas"
    exit 1
fi

echo "Caching config..."
php artisan config:cache || echo "config:cache ignoré"

echo "Caching routes..."
php artisan route:cache || echo "route:cache ignoré (peut échouer si pas de routes)"

echo "Running migrations..."
php artisan migrate --force --no-interaction || echo "Migrations ignorées ou déjà à jour"

echo "Seeding database..."
php artisan db:seed --force --no-interaction || echo "Seeding ignoré ou déjà fait"

echo "Linking storage..."
php artisan storage:link || echo "storage:link ignoré (lien existe peut-être déjà)"

echo "Permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "Démarrage de PHP-FPM..."
php-fpm -D


# Attente + debug
echo "Attente PHP-FPM (max 20s)..."
for i in {1..20}; do
    if nc -z 127.0.0.1 9000 2>/dev/null; then
        echo "PHP-FPM OK : connexion locale réussie sur 127.0.0.1:9000"
        break
    fi
    echo "Attente... ($i/20) - nc retourne code $?"
    sleep 1
done

if ! nc -z 127.0.0.1 9000 2>/dev/null; then
    echo "ERREUR FATALE : PHP-FPM inaccessible sur 127.0.0.1:9000"
    echo "Logs php-fpm récents :"
    tail -n 20 /proc/$(pgrep php-fpm)/fd/2 || echo "Pas de logs stderr"
    ps aux | grep php-fpm | grep -v grep
    netstat -tuln | grep 9000 || echo "Port 9000 non écouté"
    exit 1
fi

echo "Démarrage de Nginx..."
exec nginx -g "daemon off;"
