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
RUN npm install

## Release build

FROM wordpress:5.8.0-php8.0-apache

WORKDIR /var/www/html

COPY --from=buildjs /app/wordpress/wp-content ./wp-content
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess
COPY ./wordpress/composer.json ./
COPY ./wordpress/composer.lock ./
