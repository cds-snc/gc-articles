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
RUN npm --unsafe-perm install --only=production

## Release build

FROM wordpress:5.8.1-php8.0-apache

WORKDIR /usr/src/gc-articles/wordpress

# Update path to wordpress in apache *.conf files
RUN set -eux; \
    find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +;

# Setup self-signed cert/ssl
ARG APACHE_CERT
ARG APACHE_KEY

COPY ./wordpress/docker/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod ssl \
    && a2ensite default-ssl \
    && echo "$APACHE_CERT" > /etc/ssl/certs/self-signed.crt \
    && echo "$APACHE_KEY" > /etc/ssl/private/self-signed.key

COPY --from=buildjs /app/wordpress/wp-content ./wp-content
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess
COPY ./wordpress/vendor ../vendor

EXPOSE 80 443
