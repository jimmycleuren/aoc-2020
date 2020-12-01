FROM php:7.4-fpm

ADD . /app

RUN apt-get update
RUN apt-get install -y zip git procps

WORKDIR /app

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

#RUN composer install --verbose --prefer-dist --no-dev --optimize-autoloader --no-scripts --no-suggest