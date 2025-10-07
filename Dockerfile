# syntax=docker/dockerfile:1
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy the application
COPY backend/ .

# Expose port and run artisan serve
EXPOSE 8000

# Ensure storage and cache directories are writable
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

# Generate app key if not set at runtime
ENV APP_ENV=local
ENV APP_DEBUG=1

# Copy entrypoint
COPY backend/docker/entrypoint.sh /usr/local/bin/backend-entrypoint.sh
RUN chmod +x /usr/local/bin/backend-entrypoint.sh

CMD ["/usr/local/bin/backend-entrypoint.sh"]
