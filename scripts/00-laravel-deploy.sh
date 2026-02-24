# ── 0. Génération du .env depuis les variables d'environnement Render ────────────
echo "→ Génération du .env depuis les variables d'environnement..."

# Parse DATABASE_URL si les variables séparées ne sont pas définies
if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_HOST:-}" ]; then
    echo "→ Parsing DATABASE_URL..."
    # Format: postgresql://user:password@host:port/database
    DB_USERNAME=$(echo "$DATABASE_URL" | sed -E 's|postgresql://([^:]+):.*|\1|')
    DB_PASSWORD=$(echo "$DATABASE_URL" | sed -E 's|postgresql://[^:]+:([^@]+)@.*|\1|')
    DB_HOST=$(echo "$DATABASE_URL"     | sed -E 's|postgresql://[^@]+@([^:/]+).*|\1|')
    DB_PORT=$(echo "$DATABASE_URL"     | sed -E 's|.*:([0-9]+)/.*|\1|; t; s|.*|5432|')
    DB_DATABASE=$(echo "$DATABASE_URL" | sed -E 's|.*/([^?]+).*|\1|')
    echo "  → DB_HOST=$DB_HOST"
    echo "  → DB_DATABASE=$DB_DATABASE"
    echo "  → DB_USERNAME=$DB_USERNAME"
fi

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
