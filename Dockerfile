FROM php:8.1-apache

# Basic setup only
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Copy everything
WORKDIR /var/www/html
COPY . .

# Create basic directories
RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]