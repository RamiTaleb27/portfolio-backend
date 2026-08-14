#!/bin/bash
set -e

# Run migrations and seed on startup
php artisan migrate --force
php artisan db:seed --force || true

# Start Apache
apache2-foreground