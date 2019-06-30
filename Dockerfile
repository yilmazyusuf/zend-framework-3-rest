FROM php:7.2-apache

RUN apt-get update \
 && apt-get install -y git zlib1g-dev libicu-dev g++ \
 && docker-php-ext-install zip \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y
RUN docker-php-ext-install pdo_mysql
RUN pecl install -o -f redis \
 && rm -rf /tmp/pear \
 && docker-php-ext-enable redis \
 && docker-php-ext-configure intl \
 && docker-php-ext-install intl

WORKDIR /var/www
COPY . /var/www
