# Multi-stage Dockerfile for Laravel + Vite
# Cache buster: 2026-06-01

# 1) Node stage - build frontend assets
FROM node:18 AS node-builder
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources resources
COPY public public
RUN npm install
RUN npm run build

# 2) PHP stage - run Laravel on Apache with PDO_PGSQL
FROM php:8.2-apache

RUN apt-get update \
  && apt-get install -y git unzip libpq-dev libzip-dev zlib1g-dev libonig-dev curl --no-install-recommends \
  && docker-php-ext-install pdo pdo_pgsql mbstring zip \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# Copy composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set Apache document root to Laravel public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Copy built frontend assets from node stage into public
COPY --from=node-builder /app/public /var/www/html/public

# Install PHP dependencies and prepare app
RUN echo "BUILD_MARKER=render-dockerfile-b2fbe0b" \
  && COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Create a placeholder .env so artisan can run during build
RUN printf "APP_NAME=Laravel\nAPP_ENV=production\nAPP_KEY=base64:0123456789abcdef0123456789abcdef\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=pgsql\n" > .env

RUN php artisan key:generate --force --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
