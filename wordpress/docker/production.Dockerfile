## Install composer dependencies

FROM composer:latest AS composer
WORKDIR /app
COPY . .
RUN cd wordpress && composer install --no-interaction --optimize-autoloader --no-dev

## Install NPM dependencies

FROM node:14-alpine AS buildjs
WORKDIR /app
COPY --from=composer /app /app

RUN apk add --no-cache git
RUN npm --unsafe-perm install

## Release build

FROM wordpress:5.8.0-php8.0-apache

ARG APACHE_CERT
ARG APACHE_KEY

COPY ./wordpress/docker/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod ssl \
    && a2ensite default-ssl \
    && echo "$APACHE_CERT" > /etc/ssl/certs/self-signed.crt \
    && echo "$APACHE_KEY" > /etc/ssl/private/self-signed.key

WORKDIR /var/www/html

COPY --from=buildjs /app/wordpress/wp-content ./wp-content
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess
COPY ./wordpress/composer.json ./
COPY ./wordpress/composer.lock ./

EXPOSE 80 443
