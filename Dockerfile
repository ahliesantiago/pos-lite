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

# Copy frontend-related files
COPY resources/ ./resources/
COPY vite.config.* ./
COPY tailwind.config.* ./
COPY postcss.config.* ./

# Copy vendor to allow for SSR if needed
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

# Copy app and vendor
COPY . .
COPY --from=php-base /var/www/html/vendor ./vendor
COPY --from=node-builder /app/public ./public
COPY --from=node-builder /app/resources ./resources

# Copy Composer again for runtime usage
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy entrypoint script
COPY ./start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
