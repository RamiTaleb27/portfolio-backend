#!/bin/bash
set -e

# Ensure storage is writable
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force || true

# Start Apache
apache2-foreground