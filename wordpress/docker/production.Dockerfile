## Install composer dependencies

FROM composer:latest AS composer
WORKDIR /app
COPY . .
RUN cd wordpress && composer install --no-interaction --optimize-autoloader --no-dev

## Install NPM dependencies

FROM node:14-alpine AS buildjs
WORKDIR /app
COPY . .

RUN apk add --no-cache git
RUN npm --unsafe-perm install

## Prepare release container
FROM wordpress:5.8.1-php8.0-apache AS release

# Add build deps
RUN apk add --update --virtual mod-deps  \
    autoconf  \
    alpine-sdk \
    libmcrypt-dev \

RUN pecl install pcov \
    && docker-php-ext-enable pcov

# clean up
RUN apk del mod-deps && \
  rm -rf /apk /tmp/* /var/cache/apk/*

## Release build

FROM release

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

COPY ./wordpress/wp-content ./wp-content
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess

# Copy the vendor folder from the composer build phase and drop it outside of web root
COPY --from=composer /app/wordpress/vendor ../vendor

# Copy compiled js and css from the buildjs phase @TODO: these should be combined into a single location
COPY --from=buildjs /app/wordpress/wp-content/mu-plugins/cds-base/build ./wp-content/mu-plugins/cds-base/build
COPY --from=buildjs /app/wordpress/wp-content/mu-plugins/cds-base/classes/Modules/Contact/js ./wp-content/mu-plugins/cds-base/classes/Modules/Contact/js
COPY --from=buildjs /app/wordpress/wp-content/mu-plugins/cds-base/classes/Modules/Subscribe/js ./wp-content/mu-plugins/cds-base/classes/Modules/Subscribe/js
COPY --from=buildjs /app/wordpress/wp-content/mu-plugins/cds-base/classes/Modules/Styles/template/css ./wp-content/mu-plugins/cds-base/classes/Modules/Styles/template/css

EXPOSE 80 443
