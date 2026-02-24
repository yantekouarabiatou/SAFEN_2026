#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 1. Permissions (le plus important en premier !) ──────────────────────────────
echo "→ Correction des permissions storage & cache..."
mkdir -p storage/logs \
         storage/framework/{cache,data,sessions,views} \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Vérification rapide
if [ ! -w storage/framework/cache ]; then
    echo "ERREUR : Le dossier storage/framework/cache n'est toujours pas accessible en écriture"
    ls -la storage/framework/cache
    exit 1
fi

# ── 2. Nettoyage des caches Laravel + Spatie ─────────────────────────────────────
echo "→ Nettoyage des caches..."
php artisan config:clear    --no-interaction || true
php artisan cache:clear     --no-interaction || true
php artisan view:clear      --no-interaction || true
php artisan route:clear     --no-interaction || true
php artisan config:cache    --no-interaction || true   # optionnel en prod

# Spatie Permission : on force le reset du cache (évite beaucoup de problèmes)
echo "→ Reset cache des permissions Spatie..."
php artisan permission:cache-reset --no-interaction || true

# ── 3. Attente de la base de données (très recommandé avec Postgres + Docker) ──
echo "→ Attente de la base de données PostgreSQL..."

MAX_ATTEMPTS=30
SLEEP=2

for i in $(seq 1 $MAX_ATTEMPTS); do
    if php artisan db:monitor --once --quiet 2>/dev/null; then
        echo "→ Base de données disponible !"
        break
    fi

    echo "  Tentative $i/$MAX_ATTEMPTS - base indisponible, attente ${SLEEP}s..."
    sleep $SLEEP
done

if [ $i -eq $MAX_ATTEMPTS ]; then
    echo "ERREUR : La base de données n'est pas disponible après ${MAX_ATTEMPTS} tentatives"
    exit 1
fi

# ── 4. Migrations + Seeds (attention : migrate:fresh supprime tout !) ─────────────
echo "→ Exécution des migrations et seeds..."
php artisan migrate:fresh --seed --force --no-interaction

# Alternative plus sûre (si tu ne veux PAS tout effacer en prod) :
# php artisan migrate --force --no-interaction

# ── 5. Lien storage public ───────────────────────────────────────────────────────
echo "→ Création du lien symbolique storage..."
php artisan storage:link --force --no-interaction || true

# ── 6. Optimisations optionnelles en production ──────────────────────────────────
echo "→ Optimisations production..."
php artisan route:cache    --no-interaction || true
php artisan view:cache     --no-interaction || true
# php artisan event:cache  # si tu utilises beaucoup d'événements

# ── 7. Lancement des services ────────────────────────────────────────────────────
echo "→ Démarrage de PHP-FPM en arrière-plan..."
php-fpm -D

# Petite pause pour s'assurer que php-fpm est bien lancé
sleep 3

echo "→ Démarrage de Nginx (processus principal)..."
exec nginx -g "daemon off;"
