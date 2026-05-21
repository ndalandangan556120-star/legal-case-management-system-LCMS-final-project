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
    && docker-php-ext-install pdo_mysql zip mbstring xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Set working directory
WORKDIR /var/www/html

# Copy only composer and npm files first to leverage layer cache
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Copy full application so builds can access entry files (artisan, index.html, etc.)
COPY . .

# Install JS deps and build assets (after copying app so Vite can find entry files)
RUN npm ci --silent && npm run build --silent

# Ensure necessary directories exist and are writable
RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views public/uploads \
  && chown -R www-data:www-data bootstrap/cache storage public/uploads || true

# Install PHP dependencies (after copying app so artisan exists for package discovery)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-progress

# Final image
FROM php:8.4-apache

# Install runtime dependencies and PHP extensions (ensure PDO MySQL available)
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    default-mysql-client \
    default-libmysqlclient-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    zip \
    ca-certificates \
  && docker-php-ext-install pdo_mysql zip mbstring xml \
  && docker-php-ext-enable pdo_mysql \
  && php -r "echo 'PDO drivers: '.implode(',',PDO::getAvailableDrivers()).PHP_EOL;" \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

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

# Ensure Apache serves public folder as the document root
RUN printf '<VirtualHost *:10000>\n    ServerName localhost\n    DocumentRoot /var/www/html/public\n    <Directory /var/www/html/public>\n        Options Indexes FollowSymLinks\n        AllowOverride All\n        Require all granted\n    </Directory>\n</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf || true

EXPOSE 10000
CMD ["apache2-foreground"]
