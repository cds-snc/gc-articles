## Install composer dependencies
FROM composer:latest@sha256:f0809732b2188154b3faa8e44ab900595acb0b09cd0aa6c34e798efe4ebc9021 AS composer

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

FROM node:18-alpine@sha256:8d6421d663b4c28fd3ebc498332f249011d118945588d0a35cb9bc4b8ca09d9e AS buildjs
WORKDIR /app
COPY . .

RUN apk add --no-cache git
RUN npm --unsafe-perm install

## Release build

# when updating the Wordpress version, update the version in the files:
#     - ~/wordpress/docker/local.Dockerfile
#     - ~/github/workflows/ci.yml
FROM wordpress:6.9.1-php8.4-fpm-alpine@sha256:c634150d39bb4ca4293bc0075c93efd0a96003b1029c94fa9ca210e64f4ca918

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