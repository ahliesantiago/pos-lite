FROM node:18 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources/ ./resources/
COPY vite.config.* ./
COPY tailwind.config.* ./
COPY postcss.config.* ./

RUN npm run build

FROM php:8.1-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev \
    libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath

# Set working directory
WORKDIR /var/www/html

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app source code
COPY . .

# Copy Vite-built assets from the previous stage
COPY --from=node-builder /app/resources ./resources
COPY --from=node-builder /app/public ./public

# Install PHP dependencies (optimized for production)
RUN composer install --no-dev --optimize-autoloader

# Laravel setup
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose the port Laravel will use
EXPOSE 8000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000