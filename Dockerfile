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

# Copy all application code first
COPY . .

# Install PHP dependencies with no scripts initially
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Now run the scripts that need artisan
RUN composer dump-autoload --optimize --no-dev

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
echo "=== Starting Laravel application ==="\n\
\n\
# Check critical files\n\
echo "Checking application files..."\n\
ls -la /var/www/html/artisan\n\
ls -la /var/www/html/public/index.php\n\
\n\
# Set permissions again (critical for Railway)\n\
echo "Setting permissions..."\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 755 /var/www/html/storage\n\
chmod -R 755 /var/www/html/bootstrap/cache\n\
\n\
# Test database connection\n\
echo "Testing database connection..."\n\
timeout 10 php artisan migrate:status || echo "Database connection failed - continuing anyway"\n\
\n\
# Generate storage link if needed\n\
echo "Creating storage link..."\n\
php artisan storage:link --no-interaction || echo "Storage link failed - continuing"\n\
\n\
# Run Laravel optimizations\n\
echo "Running Laravel optimizations..."\n\
php artisan config:clear --no-interaction || echo "Config clear failed"\n\
php artisan config:cache --no-interaction || echo "Config cache failed"\n\
php artisan route:cache --no-interaction || echo "Route cache failed"\n\
php artisan view:cache --no-interaction || echo "View cache failed"\n\
php artisan filament:optimize || echo "Filament optimize not available - continuing"\n\
\n\
echo "=== Starting Apache ==="\n\
apache2-foreground\n\
' > /usr/local/bin/startup.sh && chmod +x /usr/local/bin/startup.sh

# Expose port
EXPOSE 80

# Start with optimizations
CMD ["/usr/local/bin/startup.sh"]