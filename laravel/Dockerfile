FROM php:8.1-apache

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath intl

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY ./services /var/www/html/public/

RUN mkdir -p /services

COPY ./public /services

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN cd /services && composer install --no-interaction --no-plugins --no-scripts --prefer-dist

RUN chown -R www-data:www-data /var/www/html /services
RUN chmod -R 775 /services/storage /services/bootstrap/cache

EXPOSE 80
