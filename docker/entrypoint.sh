#!/bin/sh
set -e

cd /var/www/html

if [ ! -f ".env" ] && [ -f ".env.example" ]; then
  cp .env.example .env
fi

# Sync DB settings into .env if provided via environment (POSIX-safe)
set_env_key() {
  KEY="$1"; VALUE="$2"
  [ -z "$VALUE" ] && return 0
  ESCAPED=$(printf '%s' "$VALUE" | sed -e 's/[\\&/]/\\&/g')
  if grep -q "^${KEY}=" .env; then
    sed -i "s/^${KEY}=.*/${KEY}=${ESCAPED}/" .env
  else
    printf '%s\n' "${KEY}=${VALUE}" >> .env
  fi
}

[ -n "${DB_CONNECTION}" ] && set_env_key DB_CONNECTION "${DB_CONNECTION}"
[ -n "${DB_HOST}" ] && set_env_key DB_HOST "${DB_HOST}"
[ -n "${DB_PORT}" ] && set_env_key DB_PORT "${DB_PORT}"
[ -n "${DB_DATABASE}" ] && set_env_key DB_DATABASE "${DB_DATABASE}"
[ -n "${DB_USERNAME}" ] && set_env_key DB_USERNAME "${DB_USERNAME}"
[ -n "${DB_PASSWORD}" ] && set_env_key DB_PASSWORD "${DB_PASSWORD}"

# Install composer dependencies inside the mounted volume if vendor missing or outdated
if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist --no-progress
else
  composer install --no-interaction --prefer-dist --no-progress
fi

# Wait for database if using Postgres via env
if [ "${DB_CONNECTION}" = "pgsql" ] && [ -n "${DB_HOST}" ]; then
  echo "Waiting for Postgres at ${DB_HOST}:${DB_PORT:-5432}..."
  until php -r "try { new PDO('pgsql:host=${DB_HOST};port=${DB_PORT:-5432};dbname=${DB_DATABASE:-app}', '${DB_USERNAME:-app}', '${DB_PASSWORD:-secret}'); echo 'ok'; } catch (Exception \$e) { exit(1);} "; do
    sleep 1
  done
fi

# Ensure key exists
php artisan key:generate --force || true

# Ensure storage is writable
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
chown -R www-data:www-data storage bootstrap/cache || true

# Clear config cache to ensure env vars are read
php artisan config:clear || true

# Run migrations (safe on up-to-date)
php artisan migrate --force || true

exec php artisan serve --host=0.0.0.0 --port=8000
