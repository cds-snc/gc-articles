## Install composer dependencies
FROM composer:latest AS composer

ARG WPML_USER_ID
ARG WPML_KEY

WORKDIR /app
COPY . .

RUN echo "WPML_USER_ID=$WPML_USER_ID" > .env \
    && echo "WPML_KEY=$WPML_KEY" >> .env

RUN cd wordpress && composer install --no-interaction --optimize-autoloader --no-dev

## Install NPM dependencies

FROM node:15-alpine AS buildjs
WORKDIR /app
COPY . .

RUN apk add --no-cache git
RUN npm --unsafe-perm install

## Release build

FROM wordpress:6.0.0-php8.1-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install pcov \
    && docker-php-ext-enable pcov

WORKDIR /usr/src/wordpress

# Copy PHP config
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY ./wordpress/docker/php/conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
COPY ./wordpress/docker/php/conf.d/upload_max_filesize.ini /usr/local/etc/php/conf.d/upload_max_filesize.ini
COPY ./wordpress/docker/php/conf.d/memory_limit.ini /usr/local/etc/php/conf.d/memory_limit.ini
COPY ./wordpress/docker/php/conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Copy wp-content (including installed plugins) and vendor folder from composer stage
COPY --from=composer /app/wordpress/wp-content ./wp-content
COPY --from=composer /app/wordpress/vendor ./vendor

# Copy flags
COPY ./assets/en.png /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/en.png
COPY ./assets/fr.png /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/fr.png

# Deny all web access to the vendor folder contents
RUN echo "Deny from all" > ./vendor/.htaccess

# Copy files
COPY ./wordpress/wp-config.php ./
COPY ./wordpress/.htaccess-multisite ./.htaccess
COPY ./wordpress/docker/security.txt ./.well-known/security.txt

# Copy compiled js and css from the buildjs phase
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/build ./wp-content/plugins/cds-base/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/classes/Modules/Styles/template/css ./wp-content/plugins/cds-base/classes/Modules/Styles/template/css
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-base/classes/Modules/BlocksPHP/build ./wp-content/plugins/cds-base/classes/Modules/BlocksPHP/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/gc-lists/resources/js/build ./wp-content/plugins/gc-lists/resources/js/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/gc-post-meta/resources/js/build ./wp-content/plugins/gc-post-meta/resources/js/build

VOLUME /usr/src/wordpress

USER www-data

EXPOSE 9000