#!/bin/bash

# Set correct permissions for Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Then run the Apache foreground process
exec apache2-foreground


# Generate app key if not set
if ! grep -q "APP_KEY=" /var/www/html/.env || grep -q "APP_KEY=$" /var/www/html/.env; then
    php artisan key:generate
fi
