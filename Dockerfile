# Improved Railway Dockerfile based on working commit ca0ccc0
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy application code
COPY . .

# Configure Apache Virtual Host for Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options Indexes FollowSymLinks\n\
        \n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteRule ^ index.php [L]\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create startup script for Laravel optimizations
RUN echo '#!/bin/bash\n\
# Wait for database connection\n\
until php artisan migrate:status > /dev/null 2>&1; do\n\
    echo "Waiting for database connection..."\n\
    sleep 2\n\
done\n\
\n\
# Run Laravel optimizations\n\
php artisan config:cache --no-interaction\n\
php artisan route:cache --no-interaction\n\
php artisan view:cache --no-interaction\n\
php artisan filament:optimize || true\n\
\n\
# Start Apache\n\
apache2-foreground\n\
' > /usr/local/bin/startup.sh && chmod +x /usr/local/bin/startup.sh

# Expose port
EXPOSE 80

# Start with optimizations
CMD ["/usr/local/bin/startup.sh"]