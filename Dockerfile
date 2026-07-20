# Stage 1: Build CSS/JS assets using Node
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Serve the application using serversideup/php
FROM serversideup/php:8.3-fpm-nginx

# Switch to root to install system extensions if needed
USER root
RUN install-php-extensions bcmath
USER www-data

# Copy composer files first for caching
COPY --chown=www-data:www-data composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --no-autoloader

# Copy the rest of the application files
COPY --chown=www-data:www-data . .

# Copy compiled assets from node-builder stage
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# Finish Composer autoload optimization
RUN composer dump-autoload --no-dev --optimize

# Configure environment variables for runtime automations (such as migrations)
ENV AUTORUN_ENABLED=true
ENV AUTORUN_LARAVEL_MIGRATION=true
ENV AUTORUN_LARAVEL_MIGRATION_FORCE=true
