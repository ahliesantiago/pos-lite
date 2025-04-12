#!/bin/bash
set -e  # Exit on first error
set -x  # Print each command before executing it (debug visibility)

echo ">>> Starting deploy.sh script execution <<<"

# Clear and rebuild Laravel caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force
php artisan migrate:status