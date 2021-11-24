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

## Release build

FROM wordpress:5.8.2-php8.0-apache

RUN pecl install pcov \
    && docker-php-ext-enable pcov

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

# Copy wp-content (including installed plugins) and vendor folder from composer stage
COPY --from=composer /app/wordpress/wp-content ./wp-content
COPY --from=composer /app/wordpress/vendor ./vendor

# Deny all web access to the vendor folder contents
RUN echo "Deny from all" > ./vendor/.htaccess

# Copy wp-config and .htaccess
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess

# Copy compiled js and css from the buildjs phase @TODO: these should be combined into a single location
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/build ./wp-content/plugins/cds-base/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/classes/Modules/Contact/js ./wp-content/plugins/cds-base/classes/Modules/Contact/js
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/classes/Modules/Subscribe/js ./wp-content/plugins/cds-base/classes/Modules/Subscribe/js
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/classes/Modules/Styles/template/css ./wp-content/plugins/cds-base/classes/Modules/Styles/template/css

EXPOSE 80 443
