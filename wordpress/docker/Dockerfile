FROM wordpress:5.8.1-php8.0-fpm-alpine

# update apk repositories
RUN apk update

# upgrade all
RUN apk upgrade

# install apache
RUN apk add \
    apache2 \
    apache2-ssl \
    apache2-proxy

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
