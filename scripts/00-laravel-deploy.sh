#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 0. Vérification des variables obligatoires ───────────────────────────────────
echo "→ Vérification des variables d'environnement..."
MISSING=""
[ -z "${DB_HOST:-}"     ] && MISSING="$MISSING DB_HOST"
[ -z "${DB_DATABASE:-}" ] && MISSING="$MISSING DB_DATABASE"
[ -z "${DB_USERNAME:-}" ] && MISSING="$MISSING DB_USERNAME"
[ -z "${DB_PASSWORD:-}" ] && MISSING="$MISSING DB_PASSWORD"
[ -z "${APP_KEY:-}"     ] && MISSING="$MISSING APP_KEY"

if [ -n "$MISSING" ]; then
    echo "ERREUR : Variables manquantes :$MISSING"
    exit 1
fi

echo "  DB_HOST=$DB_HOST"
echo "  DB_PORT=${DB_PORT:-5432}"
echo "  DB_DATABASE=$DB_DATABASE"
echo "  DB_USERNAME=$DB_USERNAME"

# ── 1. Génération du .env ────────────────────────────────────────────────────────
echo "→ Génération du .env..."
cat > .env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=${CACHE_STORE:-database}
SESSION_DRIVER=${SESSION_DRIVER:-database}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}

BROADCAST_CONNECTION=${BROADCAST_DRIVER:-log}
FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}

GROQ_API_KEY=${GROQ_API_KEY:-}
EOF
echo "→ .env généré"

# ── 2. Vider le cache ─────────────────────────────────────────────────────────────
echo "→ Suppression du cache de config du build..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes*.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
php artisan config:clear --no-interaction || true
php artisan cache:clear  --no-interaction || true

# ── 3. Permissions ───────────────────────────────────────────────────────────────
echo "→ Correction des permissions..."
mkdir -p storage/logs \
         storage/framework/{cache,data,sessions,views} \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── 4. Attente PostgreSQL ────────────────────────────────────────────────────────
echo "→ Attente PostgreSQL sur ${DB_HOST}:${DB_PORT:-5432}..."

MAX_ATTEMPTS=30
SLEEP=3
DB_CONNECTED=false

for i in $(seq 1 $MAX_ATTEMPTS); do
    if nc -z -w 3 "${DB_HOST}" "${DB_PORT:-5432}" 2>/dev/null; then
        echo "→ Port PostgreSQL accessible !"
        DB_CONNECTED=true
        break
    fi
    echo "  Tentative $i/$MAX_ATTEMPTS - indisponible, attente ${SLEEP}s..."
    sleep $SLEEP
done

if [ "$DB_CONNECTED" = "false" ]; then
    echo "ERREUR : Impossible d'atteindre PostgreSQL"
    exit 1
fi

# ── 5. Test connexion PDO ────────────────────────────────────────────────────────
echo "→ Test connexion PDO..."
php -r "
try {
    \$pdo = new PDO(
        'pgsql:host=${DB_HOST};port=${DB_PORT:-5432};dbname=${DB_DATABASE}',
        '${DB_USERNAME}',
        '${DB_PASSWORD}'
    );
    echo 'PDO OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'ERREUR PDO: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

# ── 6. Migrations ────────────────────────────────────────────────────────────────
echo "→ Migrations..."
if [ "${RUN_SEED:-false}" = "true" ]; then
    php artisan migrate:fresh --seed --force --no-interaction
else
    php artisan migrate --force --no-interaction
fi

# ── 7. Storage link ──────────────────────────────────────────────────────────────
echo "→ Storage link..."
php artisan storage:link --force --no-interaction || true

# ── 8. Optimisations production ──────────────────────────────────────────────────
echo "→ Optimisations production..."
php artisan config:cache --no-interaction || true
php artisan route:cache  --no-interaction || true
php artisan view:cache   --no-interaction || true

# ── 9. PHP-FPM ──────────────────────────────────────────────────────────────────
echo "→ Démarrage PHP-FPM..."
php-fpm -D
sleep 3

# Vérification corrigée : chercher le processus php-fpm par son PID
if [ ! -f /var/run/php-fpm.pid ] && ! pgrep -f "php-fpm: master" > /dev/null 2>&1; then
    echo "ERREUR : PHP-FPM n'a pas démarré"
    exit 1
fi
echo "→ PHP-FPM OK"

# ── 10. Nginx ────────────────────────────────────────────────────────────────────
echo "→ Test config Nginx..."
nginx -t 2>&1 || { echo "ERREUR config Nginx"; exit 1; }

echo "→ Démarrage Nginx..."
exec nginx -g "daemon off;"