#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : impossible de cd /var/www/html"; exit 1; }

echo "Vérification vendor/autoload.php..."
if [ ! -f vendor/autoload.php ]; then
    echo "ERREUR CRITIQUE : vendor/autoload.php introuvable !"
    exit 1
fi

echo "Création du fichier de log Laravel..."
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log

echo "Caching config..."
php artisan config:cache || echo "config:cache ignoré"

echo "Caching routes..."
php artisan route:cache || echo "route:cache ignoré"

echo "Running migrations..."
php artisan migrate --force --no-interaction || echo "Migrations ignorées"

echo "Seeding database..."
php artisan db:seed --force --no-interaction || echo "Seeding ignoré"

echo "Linking storage..."
php artisan storage:link || echo "storage:link ignoré"

echo "Permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "Démarrage de PHP-FPM..."
php-fpm -D

echo "Attente PHP-FPM (max 20s)..."
for i in {1..20}; do
    if nc -z 127.0.0.1 9000 2>/dev/null; then
        echo "PHP-FPM OK : connexion locale réussie sur 127.0.0.1:9000"
        break
    fi
    sleep 1
done

if ! nc -z 127.0.0.1 9000 2>/dev/null; then
    echo "ERREUR : PHP-FPM inaccessible"
    exit 1
fi

echo "Test rapide de Laravel..."
php artisan --version || echo "Laravel inaccessible"

echo "Affichage des derniers logs Laravel..."
tail -n 50 storage/logs/laravel.log 2>/dev/null || echo "Pas encore de logs"

echo "Démarrage de Nginx..."
exec nginx -g "daemon off;"
```

---

## 📝 Et active APP_DEBUG temporairement

Dans Render → **Environment** :
```
APP_DEBUG=true