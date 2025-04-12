# PHP Stage - base for composer & artisan setup
FROM php:8.3-fpm AS php-base

RUN apt-get update && apt-get install -y \
    curl git libonig-dev libpq-dev \
    libxml2-dev libzip-dev unzip zip \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy PHP app files and install dependencies
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Node/Vite Builder Stage (after vendor is installed)
FROM node:18 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

# Copy only frontend-related files
COPY resources/ ./resources/
COPY vite.config.* ./
COPY tailwind.config.* ./
COPY postcss.config.* ./

COPY --from=php-base /var/www/html/vendor ./vendor

RUN npm run build

# Final PHP Production Image
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
  curl git libonig-dev libpq-dev \
  libxml2-dev libzip-dev unzip zip \
  && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
COPY ./deploy.sh ./deploy.sh
RUN chmod +x deploy.sh

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy built frontend and vendor
COPY --from=node-builder /app/public ./public
COPY --from=node-builder /app/resources ./resources

# Copy vendor again (if needed)
COPY --from=php-base /var/www/html/vendor ./vendor

RUN composer dump-autoload --optimize \
  && chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000