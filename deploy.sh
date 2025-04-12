#!/bin/bash
echo "=== Starting deploy.sh script execution ==="

# Clear and rebuild Laravel caches
echo "=== Clearing and rebuilding Laravel caches ==="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations
echo "=== Running migrations ==="
php artisan migrate --force