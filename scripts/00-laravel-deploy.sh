#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 0. Génération du .env depuis les variables d'environnement Render ────────────
echo "→ Génération du .env depuis les variables d'environnement..."
cat > .env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"

CACHE_DRIVER="${CACHE_DRIVER:-file}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

BROADCAST_DRIVER="${BROADCAST_DRIVER:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
EOF

echo "→ .env généré avec succès"

# ── 1. Permissions ───────────────────────────────────────────────────────────────
echo "→ Correction des permissions storage & cache..."
mkdir -p storage/logs \
         storage/framework/{cache,data,sessions,views} \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

if [ ! -w storage/framework/cache ]; then
    echo "ERREUR : storage/framework/cache non accessible en écriture"
    ls -la storage/framework/cache
    exit 1
fi

# ── 2. Nettoyage caches ──────────────────────────────────────────────────────────
echo "→ Nettoyage des caches Laravel..."
php artisan config:clear  --no-interaction || true
php artisan cache:clear   --no-interaction || true
php artisan view:clear    --no-interaction || true
php artisan route:clear   --no-interaction || true
php artisan permission:cache-reset --no-interaction || true

# ── 3. Génération de la clé si absente ──────────────────────────────────────────
if [ -z "${APP_KEY:-}" ]; then
    echo "→ Génération de APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# ── 4. Attente de la connexion à PostgreSQL ──────────────────────────────────────
echo "→ Attente de la connexion à PostgreSQL..."
echo "  DB_HOST=${DB_HOST:-non défini}"
echo "  DB_PORT=${DB_PORT:-5432}"
echo "  DB_DATABASE=${DB_DATABASE:-non défini}"
echo "  DB_USERNAME=${DB_USERNAME:-non défini}"

MAX_ATTEMPTS=30
SLEEP=3
DB_CONNECTED=false

for i in $(seq 1 $MAX_ATTEMPTS); do
    if php artisan db:show --no-interaction > /dev/null 2>&1; then
        echo "→ Base de données connectée !"
        DB_CONNECTED=true
        break
    fi
    echo "  Tentative $i/$MAX_ATTEMPTS - base indisponible, attente ${SLEEP}s..."
    sleep $SLEEP
done

if [ "$DB_CONNECTED" = "false" ]; then
    echo ""
    echo "ERREUR : Impossible de se connecter à la base après ${MAX_ATTEMPTS} tentatives"
    echo "Vérifiez :"
    echo "  - DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD dans Render env vars"
    echo "  - Que le service PostgreSQL Render est bien lancé et accessible"
    echo "  - Utilisez le Internal Database URL si même région"
    exit 1
fi

# ── 5. Migrations ────────────────────────────────────────────────────────────────
echo "→ Exécution des migrations..."
if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "→ Mode seed activé : migrate:fresh + seed"
    php artisan migrate:fresh --seed --force --no-interaction
else
    php artisan migrate --force --no-interaction
fi

# ── 6. Storage link ──────────────────────────────────────────────────────────────
echo "→ Création du lien symbolique storage..."
php artisan storage:link --force --no-interaction || true

# ── 7. Optimisations production ──────────────────────────────────────────────────
echo "→ Optimisations production..."
php artisan config:cache  --no-interaction || true
php artisan route:cache   --no-interaction || true
php artisan view:cache    --no-interaction || true

# ── 8. Démarrage PHP-FPM ────────────────────────────────────────────────────────
echo "→ Démarrage PHP-FPM..."
php-fpm -D
sleep 2

# ── 9. Démarrage Nginx ──────────────────────────────────────────────────────────
echo "→ Démarrage Nginx (processus principal)..."
exec nginx -g "daemon off;"
