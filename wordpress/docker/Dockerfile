## Install composer dependencies
FROM composer:latest@sha256:d8f6343d3fae98107426bc49163ccad46ef85aabd4a27d80a74401fab4aba332 AS composer

ARG WPML_USER_ID
ARG WPML_KEY

WORKDIR /app
COPY . .

RUN echo "WPML_USER_ID=$WPML_USER_ID" > .env \
    && echo "WPML_KEY=$WPML_KEY" >> .env

RUN cd wordpress \
    && composer install --no-interaction --optimize-autoloader --no-dev \
    && composer check-platform-reqs

## Install NPM dependencies

FROM node:24-alpine@sha256:e67514e5d0f6c46656005e1b693b2ec9d52e80b641307de684d4a015ba7a4eaf AS buildjs
WORKDIR /app
COPY . .

RUN apk add --no-cache \
    g++ \
    git \
    make \
    python3 \
    py3-pip
RUN npm --unsafe-perm install

## Release build

# when updating the Wordpress version, update the version in the files:
#     - ~/wordpress/docker/local.Dockerfile
#     - ~/github/workflows/ci.yml
FROM wordpress:7.1.0-php8.4-fpm-alpine@sha256:ae1cb25cccbdbe7c5c5a419e8a16d0d1afed94bdd04da67fc8b44198f64506ec

RUN apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install pcov \
    && docker-php-ext-enable pcov

WORKDIR /usr/src/wordpress

# Copy PHP config
RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY ./wordpress/docker/php/conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
COPY ./wordpress/docker/php/conf.d/upload_max_filesize.ini /usr/local/etc/php/conf.d/upload_max_filesize.ini
COPY ./wordpress/docker/php/conf.d/memory_limit.ini /usr/local/etc/php/conf.d/memory_limit.ini
COPY ./wordpress/docker/php/conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY ./wordpress/docker/php/conf.d/expose_php.ini /usr/local/etc/php/conf.d/expose_php.ini

# Copy PHP-FPM config
COPY ./wordpress/docker/php/conf.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Copy wp-content (including installed plugins) and vendor folder from composer stage
COPY --from=composer /app/wordpress/wp-content ./wp-content
COPY --from=composer /app/wordpress/vendor ./vendor

# Copy flags
COPY ./assets/en.png /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/en.png
COPY ./assets/en.svg /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/en.svg
COPY ./assets/fr.png /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/fr.png
COPY ./assets/fr.svg /usr/src/wordpress/wp-content/plugins/sitepress-multilingual-cms/res/flags/fr.svg

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
COPY --from=buildjs /app/wordpress/wp-content/plugins/gc-post-meta/resources/js/build ./wp-content/plugins/gc-post-meta/resources/js/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-wpml-mods/resources/js/build ./wp-content/plugins/cds-wpml-mods/resources/js/build

# Create a writable debug.log
RUN touch ./wp-content/debug.log \
    && chmod 660 ./wp-content/debug.log \
    && chown www-data:www-data ./wp-content/debug.log

VOLUME /usr/src/wordpress

USER www-data

EXPOSE 9000