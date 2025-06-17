FROM php:8.2-apache

# Cài ext PHP
RUN apt-get update && apt-get install -y zip unzip && docker-php-ext-install pdo pdo_mysql

# Cài redis ext
RUN pecl install redis && docker-php-ext-enable redis

# Bật mod_rewrite cho Apache
RUN a2enmod rewrite

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
