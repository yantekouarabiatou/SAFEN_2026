#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 0. Parse DATABASE_URL si les variables séparées ne sont pas définies ─────────
if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_HOST:-}" ]; then
    echo "→ Parsing DATABASE_URL..."
    DB_USERNAME=$(echo "$DATABASE_URL" | sed -E 's|postgresql://([^:]+):.*|\1|')
    DB_PASSWORD=$(echo "$DATABASE_URL" | sed -E 's|postgresql://[^:]+:([^@]+)@.*|\1|')
    DB_HOST=$(echo "$DATABASE_URL"     | sed -E 's|postgresql://[^@]+@([^:/]+).*|\1|')
    DB_PORT=$(echo "$DATABASE_URL"     | sed -E 's|.*:([0-9]+)/.*|\1|; t; s|.*|5432|')
    DB_DATABASE=$(echo "$DATABASE_URL" | sed -E 's|.*/([^?]+).*|\1|')
    echo "  → DB_HOST=$DB_HOST"
    echo "  → DB_DATABASE=$DB_DATABASE"
    echo "  → DB_USERNAME=$DB_USERNAME"
fi

# ── 1. Génération du .env ────────────────────────────────────────────────────────
echo "→ Génération du .env depuis les variables d'environnement..."
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
echo "→ .env généré avec succès"

# ── 2. Permissions ───────────────────────────────────────────────────────────────
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

# ── 3. Nettoyage caches ──────────────────────────────────────────────────────────
echo "→ Nettoyage des caches Laravel..."
php artisan config:clear  --no-interaction || true
php artisan cache:clear   --no-interaction || true
php artisan view:clear    --no-interaction || true
php artisan route:clear   --no-interaction || true
php artisan permission:cache-reset --no-interaction || true

# ── 4. Génération de la clé si absente ──────────────────────────────────────────
if [ -z "${APP_KEY:-}" ]; then
    echo "→ Génération de APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# ── 5. Attente de la connexion à PostgreSQL ──────────────────────────────────────
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
    echo "Vérifiez DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD dans Render env vars"
    exit 1
fi

# ── 6. Migrations ────────────────────────────────────────────────────────────────
echo "→ Exécution des migrations..."
if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "→ Mode seed activé : migrate:fresh + seed"
    php artisan migrate:fresh --seed --force --no-interaction
else
    php artisan migrate --force --no-interaction
fi

# ── 7. Storage link ──────────────────────────────────────────────────────────────
echo "→ Création du lien symbolique storage..."
php artisan storage:link --force --no-interaction || true

# ── 8. Optimisations production ──────────────────────────────────────────────────
echo "→ Optimisations production..."
php artisan config:cache --no-interaction || true
php artisan route:cache  --no-interaction || true
php artisan view:cache   --no-interaction || true

# ── 9. Démarrage PHP-FPM ────────────────────────────────────────────────────────
echo "→ Démarrage PHP-FPM..."
php-fpm -D

sleep 2

if ! pgrep php-fpm > /dev/null 2>&1; then
    echo "ERREUR : PHP-FPM n'a pas démarré"
    exit 1
fi
echo "→ PHP-FPM démarré avec succès"

# ── 10. Test configuration Nginx ────────────────────────────────────────────────
echo "→ Test de la configuration Nginx..."
if ! nginx -t 2>&1; then
    echo "ERREUR : Configuration Nginx invalide"
    exit 1
fi

# ── 11. Démarrage Nginx ─────────────────────────────────────────────────────────
echo "→ Démarrage Nginx (processus principal)..."
exec nginx -g "daemon off;"
