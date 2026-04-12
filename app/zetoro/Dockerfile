# Stage 1: Base PHP Setup
FROM php:8.3-cli AS base

# Install system dependencies and PostgreSQL drivers
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev libicu-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Stage 2: Build Node/Vite Assets
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .

# This runs Vite and compiles your JS/CSS into the public/build directory
RUN npm run build 

# Stage 3: Final Production Image
FROM base

# Copy the Laravel application code
COPY . .

# Copy the compiled frontend assets from the Node stage
COPY --from=node_builder /app/public/build ./public/build

# Install production PHP dependencies (ignores dev packages like Pest/PHPUnit)
RUN composer install --optimize-autoloader --no-dev

# Ensure Laravel has permission to write to cache and storage logs
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
