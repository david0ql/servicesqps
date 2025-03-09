FROM php:8.1-apache

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

ENV PHP_MEMORY_LIMIT=1G
ENV PHP_POST_MAX_SIZE=1G
ENV PHP_UPLOAD_MAX_FILESIZE=1G
ENV PHP_MAX_EXECUTION_TIME=300
ENV PHP_MAX_INPUT_TIME=300
ENV PHP_MAX_INPUT_VARS=1000
ENV PHP_DISPLAY_ERRORS=On
ENV PHP_DISPLAY_STARTUP_ERRORS=On
ENV PHP_ERROR_REPORTING=E_ALL
ENV PHP_LOG_ERRORS=On
ENV PHP_LOG_ERRORS_MAX_LEN=1024
ENV PHP_IGNORE_REPEATED_ERRORS=Off
ENV PHP_IGNORE_REPEATED_SOURCE=Off
ENV PHP_REPORT_MEMLEAKS=On
ENV PHP_HTML_ERRORS=On
ENV PHP_ERROR_LOG=/dev/stderr

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
