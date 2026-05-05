#!/bin/bash
set -e

# Set correct permissions for Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure dependencies exist when source is bind-mounted from host
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate app key if not set
if ! grep -q "^APP_KEY=" /var/www/html/.env || grep -q "APP_KEY=$" /var/www/html/.env; then
    echo "Generating Laravel APP_KEY..."
    php artisan key:generate --force
fi

# Finally run Apache in the foreground
exec apache2-foreground
