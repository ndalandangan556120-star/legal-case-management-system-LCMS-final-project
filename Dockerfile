# Multi-stage Dockerfile to produce smaller final image for Render
FROM php:8.4-cli AS builder

# Install system packages, PHP extensions and Node.js for building
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    zip \
    ca-certificates \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs build-essential \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer and npm files first to leverage layer cache
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Install PHP and JS dependencies and build assets
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader || true
RUN npm ci --silent || true && npm run build --silent || true

# Copy application files
COPY . .

# Ensure necessary directories exist and are writable
RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views public/uploads \
  && chown -R www-data:www-data bootstrap/cache storage public/uploads || true

# Re-run composer install to ensure vendor is present (if not from cache)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Final image
FROM php:8.4-apache

# Enable modules and small runtime tweaks
RUN a2enmod rewrite || true
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf || true
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf || true

WORKDIR /var/www/html

# Copy built app from builder
COPY --from=builder /var/www/html /var/www/html

# Install Composer binary (optional for runtime artisan tasks)
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

# Ensure storage permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views public/uploads bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache public/uploads || true

# Clear caches (best-effort during build)
RUN php artisan config:clear || true && php artisan route:clear || true && php artisan view:clear || true

EXPOSE 10000
CMD ["apache2-foreground"]
