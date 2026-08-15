FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    ca-certificates \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Download TiDB CA certificate
RUN curl -fsSL https://letsencrypt.org/certs/isrgrootx1.pem -o /usr/local/share/ca-certificates/isrgrootx1.crt \
    && update-ca-certificates

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Verify controllers exist (busts cache!)
RUN ls -la app/Http/Controllers/Api/

# Create storage directories and fix permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create storage link
RUN php artisan storage:link || true

# Enable Apache rewrite
RUN a2enmod rewrite

# Update Apache config to point to public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Final permission fix
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start with entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]