#!/bin/bash
set -e

# Set correct permissions for Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if ! grep -q "^APP_KEY=" /var/www/html/.env || grep -q "APP_KEY=$" /var/www/html/.env; then
    echo "Generating Laravel APP_KEY..."
    php artisan key:generate
fi

# Finally run Apache in the foreground
exec apache2-foreground
