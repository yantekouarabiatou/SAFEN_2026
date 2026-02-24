#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 0. Parse DATABASE_URL avec Python (robuste, gère port absent) ────────────────
if [ -n "${DATABASE_URL:-}" ]; then
    echo "→ Parsing DATABASE_URL via Python..."

    eval $(python3 -c "
import urllib.parse, sys

url = '${DATABASE_URL}'
# Remplacer postgresql:// par http:// pour que urlparse le comprenne
parsed = urllib.parse.urlparse(url.replace('postgresql://', 'http://').replace('postgres://', 'http://'))

db_username = parsed.username or ''
db_password = parsed.password or ''
db_host     = parsed.hostname or ''
db_port     = str(parsed.port) if parsed.port else '5432'
db_database = parsed.path.lstrip('/') if parsed.path else ''

print(f'export DB_USERNAME=\"{db_username}\"')
print(f'export DB_PASSWORD=\"{db_password}\"')
print(f'export DB_HOST=\"{db_host}\"')
print(f'export DB_PORT=\"{db_port}\"')
print(f'export DB_DATABASE=\"{db_database}\"')
")

    echo "  → DB_HOST=$DB_HOST"
    echo "  → DB_PORT=$DB_PORT"
    echo "  → DB_DATABASE=$DB_DATABASE"
    echo "  → DB_USERNAME=$DB_USERNAME"
fi

# Vérification que les variables essentielles sont définies
if [ -z "${DB_HOST:-}" ] || [ -z "${DB_DATABASE:-}" ] || [ -z "${DB_USERNAME:-}" ]; then
    echo "ERREUR : Variables DB manquantes après parsing."
    echo "  DB_HOST='${DB_HOST:-}'"
    echo "  DB_DATABASE='${DB_DATABASE:-}'"
    echo "  DB_USERNAME='${DB_USERNAME:-}'"
    echo "Ajoutez DATABASE_URL ou DB_HOST/DB_DATABASE/DB_USERNAME dans Render env vars."
    exit 1
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

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD:-}
DATABASE_URL=${DATABASE_URL:-}

CACHE_DRIVER="${CACHE_DRIVER:-file}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

BROADCAST_DRIVER="${BROADCAST_DRIVER:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
EOF
echo "→ .env généré"
echo "--- Vérification .env DB ---"
grep "^DB_" .env
echo "----------------------------"

# ── 2. Vider le cache de config du build ─────────────────────────────────────────
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

# ── 4. APP_KEY ───────────────────────────────────────────────────────────────────
if [ -z "${APP_KEY:-}" ]; then
    echo "→ Génération de APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# ── 5. Attente PostgreSQL via TCP ────────────────────────────────────────────────
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
    echo "ERREUR : Impossible d'atteindre PostgreSQL sur ${DB_HOST}:${DB_PORT:-5432}"
    exit 1
fi

# ── 6. Test connexion PDO ────────────────────────────────────────────────────────
echo "→ Test connexion PDO..."
php -r "
try {
    \$pdo = new PDO(
        'pgsql:host=${DB_HOST};port=${DB_PORT:-5432};dbname=${DB_DATABASE}',
        '${DB_USERNAME}',
        '${DB_PASSWORD:-}'
    );
    echo 'PDO OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'ERREUR PDO: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

# ── 7. Migrations ────────────────────────────────────────────────────────────────
echo "→ Migrations..."
if [ "${RUN_SEED:-false}" = "true" ]; then
    php artisan migrate:fresh --seed --force --no-interaction
else
    php artisan migrate --force --no-interaction
fi

# ── 8. Storage link ──────────────────────────────────────────────────────────────
echo "→ Storage link..."
php artisan storage:link --force --no-interaction || true

# ── 9. Optimisations production ──────────────────────────────────────────────────
echo "→ Optimisations production..."
php artisan config:cache --no-interaction || true
php artisan route:cache  --no-interaction || true
php artisan view:cache   --no-interaction || true

# ── 10. PHP-FPM ─────────────────────────────────────────────────────────────────
echo "→ Démarrage PHP-FPM..."
php-fpm -D
sleep 2

if ! pgrep php-fpm > /dev/null 2>&1; then
    echo "ERREUR : PHP-FPM n'a pas démarré"
    exit 1
fi
echo "→ PHP-FPM OK"

# ── 11. Nginx ────────────────────────────────────────────────────────────────────
echo "→ Test config Nginx..."
nginx -t 2>&1 || { echo "ERREUR config Nginx"; exit 1; }

echo "→ Démarrage Nginx..."
exec nginx -g "daemon off;"
