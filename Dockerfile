# syntax=docker/dockerfile:1

# -----------------------------
# Frontend build (Vite)
# -----------------------------
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build


# -----------------------------
# PHP dependencies (Composer)
# -----------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress \
    --no-scripts


# -----------------------------
# Production Laravel application
# -----------------------------
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Laravel 12 requires PHP 8.2+. This image uses PHP 8.3.
# PostgreSQL support is provided by pdo_pgsql/pgsql for Supabase.
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        curl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Install Composer dependencies.
COPY --from=vendor /app/vendor ./vendor

# Copy application source.
COPY . .

# Copy the Vite production build generated in the Node stage.
COPY --from=frontend /app/public/build ./public/build

# Use Laravel's public directory as Apache's document root and allow
# public/.htaccess to handle Laravel's front-controller routing.
RUN sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's!<Directory /var/www/>!<Directory /var/www/html/public>!g' \
        /etc/apache2/apache2.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' \
        /etc/apache2/apache2.conf

# Generate Laravel's package discovery manifest after the application is present.
RUN php artisan package:discover --ansi

# Ensure Laravel can write runtime files.
RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
