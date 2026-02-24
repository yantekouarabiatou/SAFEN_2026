#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html || { echo "ERREUR : Impossible d'accéder à /var/www/html"; exit 1; }

echo "=========================================="
echo "   Démarrage de l'application Laravel    "
echo "=========================================="

# ── 1. Permissions (priorité absolue) ────────────────────────────────────────────
echo "→ Correction des permissions storage & cache..."
mkdir -p storage/logs \
         storage/framework/{cache,data,sessions,views} \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Vérification
if [ ! -w storage/framework/cache ]; then
    echo "ERREUR : storage/framework/cache non accessible en écriture"
    ls -la storage/framework/cache
    exit 1
fi

# ── 2. Nettoyage caches ──────────────────────────────────────────────────────────
echo "→ Nettoyage des caches Laravel + Spatie..."
php artisan config:clear  --no-interaction || true
php artisan cache:clear   --no-interaction || true
php artisan view:clear    --no-interaction || true
php artisan route:clear   --no-interaction || true

# Spatie spécifique
php artisan permission:cache-reset --no-interaction || true

# ── 3. Attente de la base de données PostgreSQL (méthode fiable) ────────────────
echo "→ Attente de la connexion à PostgreSQL..."

MAX_ATTEMPTS=30
SLEEP=3  # Augmenté un peu pour Postgres qui peut être lent au démarrage

for i in $(seq 1 $MAX_ATTEMPTS); do
    if php -r "require 'vendor/autoload.php'; echo (new Illuminate\Database\Capsule\Manager(require 'bootstrap/app.php'))->get('db')->connection()->getPdo() ? 'OK' : 'KO';" 2>/dev/null | grep -q "OK"; then
        echo "→ Base de données connectée !"
        break
    fi

    echo "  Tentative $i/$MAX_ATTEMPTS - base indisponible, attente ${SLEEP}s..."
    sleep $SLEEP
done

if [ $i -eq $MAX_ATTEMPTS ]; then
    echo "ERREUR : Impossible de se connecter à la base après ${MAX_ATTEMPTS} tentatives"
    echo "Vérifiez :"
    echo "  - DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env / Render env vars"
    echo "  - Que le service PostgreSQL Render est bien lancé et accessible"
    exit 1
fi

# ── 4. Migrations (sécurisé : pas de fresh en prod par défaut) ───────────────────
echo "→ Exécution des migrations..."

# Option : activer fresh + seed seulement si variable d'env RUN_SEED=true
if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "→ Mode seed activé : migrate:fresh + seed"
    php artisan migrate:fresh --seed --force --no-interaction
else
    php artisan migrate --force --no-interaction
fi

# ── 5. Storage link ──────────────────────────────────────────────────────────────
echo "→ Création du lien symbolique storage..."
php artisan storage:link --force --no-interaction || true

# ── 6. Optimisations prod (optionnel mais recommandé) ────────────────────────────
echo "→ Optimisations production..."
php artisan config:cache  --no-interaction || true
php artisan route:cache   --no-interaction || true
php artisan view:cache    --no-interaction || true

# ── 7. Services ──────────────────────────────────────────────────────────────────
echo "→ Démarrage PHP-FPM..."
php-fpm -D

sleep 3  # Laisser le temps à php-fpm de s'initialiser

echo "→ Démarrage Nginx (processus principal)..."
exec nginx -g "daemon off;"
