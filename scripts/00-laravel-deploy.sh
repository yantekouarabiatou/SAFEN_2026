#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 0. Parse DATABASE_URL ────────────────────────────────────────────────────────
if [ -n "${DATABASE_URL:-}" ]; then
    echo "→ Parsing DATABASE_URL..."
    DB_USERNAME=$(echo "$DATABASE_URL" | sed -E 's|postgresql://([^:]+):.*|\1|')
    DB_PASSWORD=$(echo "$DATABASE_URL" | sed -E 's|postgresql://[^:]+:([^@]+)@.*|\1|')
    DB_HOST=$(echo "$DATABASE_URL"     | sed -E 's|postgresql://[^@]+@([^:/]+).*|\1|')
    DB_PORT_RAW=$(echo "$DATABASE_URL" | sed -E 's|postgresql://[^@]+@[^:/]+:?([0-9]*).*|\1|')
    DB_PORT="${DB_PORT_RAW:-5432}"
    DB_DATABASE=$(echo "$DATABASE_URL" | sed -E 's|.*/([^?]+)(\?.*)?$|\1|')
    export DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
    echo "  → DB_HOST=$DB_HOST"
    echo "  → DB_PORT=$DB_PORT"
    echo "  → DB_DATABASE=$DB_DATABASE"
    echo "  → DB_USERNAME=$DB_USERNAME"
fi

# ── 1. Génération du .env ────────────────────────────────────────────────────────
echo "→ Génération du .env..."
cat > .env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="pgsql"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"
DATABASE_URL="${DATABASE_URL:-}"

CACHE_DRIVER="${CACHE_DRIVER:-file}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

BROADCAST_DRIVER="${BROADCAST_DRIVER:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
EOF
echo "→ .env généré"

# ── 2. Vider TOUS les caches pour forcer la relecture du .env ────────────────────
echo "→ Vidage des caches..."
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

# ── 4. APP_KEY ───────────────────────────────────────────────────────────────────
if [ -z "${APP_KEY:-}" ]; then
    echo "→ Génération de APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# ── 5. Attente PostgreSQL via connexion TCP directe (nc) ─────────────────────────
echo "→ Attente de la connexion à PostgreSQL..."
echo "  DB_HOST=${DB_HOST:-non défini}"
echo "  DB_PORT=${DB_PORT:-5432}"
echo "  DB_DATABASE=${DB_DATABASE:-non défini}"

MAX_ATTEMPTS=30
SLEEP=3
DB_CONNECTED=false

for i in $(seq 1 $MAX_ATTEMPTS); do
    # Test TCP direct avec nc (netcat) — ne dépend pas de Laravel ni du .env cache
    if nc -z -w 3 "${DB_HOST:-localhost}" "${DB_PORT:-5432}" 2>/dev/null; then
        echo "→ Port PostgreSQL accessible !"
        DB_CONNECTED=true
        break
    fi
    echo "  Tentative $i/$MAX_ATTEMPTS - base indisponible, attente ${SLEEP}s..."
    sleep $SLEEP
done

if [ "$DB_CONNECTED" = "false" ]; then
    echo ""
    echo "ERREUR : Impossible d'atteindre PostgreSQL sur ${DB_HOST:-?}:${DB_PORT:-5432}"
    echo "Vérifiez les variables d'environnement dans Render."
    exit 1
fi

# Test de connexion réelle avec PHP après confirmation TCP
echo "→ Test de connexion PHP/PDO..."
php -r "
    try {
        \$pdo = new PDO(
            'pgsql:host=${DB_HOST};port=${DB_PORT:-5432};dbname=${DB_DATABASE}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}'
        );
        echo 'Connexion PDO OK' . PHP_EOL;
    } catch (Exception \$e) {
        echo 'ERREUR PDO: ' . \$e->getMessage() . PHP_EOL;
        exit(1);
    }
"

# ── 6. Migrations ────────────────────────────────────────────────────────────────
echo "→ Exécution des migrations..."
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
sleep 2

if ! pgrep php-fpm > /dev/null 2>&1; then
    echo "ERREUR : PHP-FPM n'a pas démarré"
    exit 1
fi
echo "→ PHP-FPM OK"

# ── 10. Nginx ───────────────────────────────────────────────────────────────────
echo "→ Test config Nginx..."
nginx -t 2>&1 || { echo "ERREUR : config Nginx invalide"; exit 1; }

echo "→ Démarrage Nginx..."
exec nginx -g "daemon off;"
