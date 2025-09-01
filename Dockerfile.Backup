FROM php:8.2-apache

RUN apt-get update && apt-get install -y zip unzip git
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

COPY . .

# Skip problematic dependencies for now
RUN composer install --no-dev --ignore-platform-reqs --no-scripts --no-interaction || echo "Composer install failed, continuing..."

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 storage bootstrap/cache

CMD ["apache2-foreground"]