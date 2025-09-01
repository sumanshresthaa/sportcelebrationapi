FROM richarvey/nginx-php-fpm:3.1.6

USER root
RUN apk update && apk add --no-cache nodejs npm

# Copy project into container
COPY . /var/www/html

# Install composer dependencies
WORKDIR /var/www/html
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render-specific env flags
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# Laravel production defaults
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr



CMD php artisan migrate --force && /start.sh
