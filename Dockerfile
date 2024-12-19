FROM composer:2.8

FROM php:8.4-fpm
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt update -y
RUN apt install git zip unzip -y
RUN docker-php-ext-install bcmath

USER www-data
WORKDIR /app
ADD --chown=www-data:www-data . .
RUN composer install --no-dev