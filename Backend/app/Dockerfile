# Set master image
FROM php:7.4-fpm-alpine

# Set working directory
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

RUN apk update && apk add libzip-dev
# Extension zip for laravel
RUN docker-php-ext-install zip 

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require laravel/installer