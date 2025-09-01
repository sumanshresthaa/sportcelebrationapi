FROM richarvey/nginx-php-fpm:3.1.6

USER root
RUN apk update && apk add --no-cache nodejs npm

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project into /var/www/html
COPY . /var/www/html

# Render-specific env flags
ENV SKIP_COMPOSER 1
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

