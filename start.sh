#!/bin/bash
set -e  # Exit on first error

echo ">>> Laravel boot script <<<"

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
echo ">>> Running migrations <<<"
php artisan migrate --force
# php artisan db:seed --force
php artisan migrate:status

exec php artisan serve --host=0.0.0.0 --port=8000