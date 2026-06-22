#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set." >&2
    exit 1
fi

cd /var/www/html

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Waiting for database..."
until php artisan migrate --force --no-interaction 2>/dev/null; do
    echo "Database not ready, retrying in 3s..."
    sleep 3
done

echo "Starting application..."
exec "$@"
