#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    echo "Creating .env from .env.docker.example"
    cp .env.docker.example .env
fi

if [ ! -f vendor/autoload.php ]; then
    echo "Installing Composer dependencies"
    composer install --no-interaction --prefer-dist
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

echo "Waiting for the database at ${DB_HOST:-db}"
until mariadb-admin ping -h"${DB_HOST:-db}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent >/dev/null 2>&1; do
    sleep 2
done

mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear >/dev/null

exec "$@"
