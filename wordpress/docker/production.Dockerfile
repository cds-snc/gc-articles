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

FROM wordpress:5.8.2-php8.0-fpm-alpine

RUN pecl install pcov \
    && docker-php-ext-enable pcov

WORKDIR /usr/src/gc-articles/wordpress

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

# @TODO: do we need 443 here anymore?
EXPOSE 80 443
