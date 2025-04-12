#!/bin/bash

# Clear and rebuild Laravel caches
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force