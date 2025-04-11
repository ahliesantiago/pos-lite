FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
  git curl zip unzip libpq-dev \
  libonig-dev libxml2-dev libzip-dev \
  nodejs npm
  docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Install Composer and Vite dependencies
RUN composer install --no-dev --optimize-autoloader
  npm install && npm run build

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000