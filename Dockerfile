# Simplified Railway Dockerfile - Dynamic Port Support
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

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod php8.2

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all application code
COPY . .

# Install PHP dependencies with no scripts initially
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Run composer dump-autoload
RUN composer dump-autoload --optimize --no-dev

# Simple Apache configuration template
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    ServerName localhost\n\
    \n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php index.html\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create a simple PHP test file
RUN echo '<?php phpinfo(); ?>' > /var/www/html/public/test.php

# Create startup script
RUN printf '#!/bin/bash\n\
echo "=== Railway Laravel Startup ==="\n\
\n\
echo "PHP Version:"\n\
php -v\n\
\n\
echo "Checking Laravel files:"\n\
ls -la /var/www/html/public/index.php\n\
ls -la /var/www/html/public/test.php\n\
\n\
echo "Testing PHP syntax:"\n\
php -l /var/www/html/public/index.php\n\
php -l /var/www/html/public/test.php\n\
\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 755 /var/www/html/storage\n\
chmod -R 755 /var/www/html/bootstrap/cache\n\
\n\
echo "Configuring Apache for port ${PORT:-80}"\n\
sed -i "s/*:80/*:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf\n\
\n\
echo "Laravel optimizations:"\n\
timeout 5 php artisan config:cache || echo "Skipping config cache"\n\
timeout 5 php artisan route:cache || echo "Skipping route cache"\n\
timeout 5 php artisan view:cache || echo "Skipping view cache"\n\
\n\
echo "Starting Apache on port ${PORT:-80}..."\n\
apache2-foreground\n\
' > /usr/local/bin/startup.sh && chmod +x /usr/local/bin/startup.sh

# Expose dynamic port
EXPOSE ${PORT:-80}

# Start with our custom script
CMD ["/usr/local/bin/startup.sh"]