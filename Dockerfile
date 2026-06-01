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
  && apt-get install -y git unzip libpq-dev libzip-dev zlib1g-dev libonig-dev --no-install-recommends \
  && docker-php-ext-install pdo pdo_pgsql mbstring zip \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# Copy composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Copy built frontend assets from node stage into public
COPY --from=node-builder /app/public /var/www/html/public

# Install PHP dependencies and prepare app
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
  && php artisan key:generate --force \
  && php artisan config:cache \
  && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
