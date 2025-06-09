FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip mbstring xml

WORKDIR /var/www/symfony

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer