## Install composer dependencies
FROM composer:latest@sha256:3df281ca83119bff1332f0f5f311960cb16b99238f8273acd965be6bd508aa8e AS composer

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
FROM wordpress:6.8.1-php8.1-fpm-alpine@sha256:80133d7ca934fa288e4055cb4960d9edd592dad096099e2f7397f205af63a8c3

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
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-web-blocks/resources/js/build ./wp-content/plugins/cds-web-blocks/resources/js/build
COPY --from=buildjs /app/wordpress/wp-content/plugins/cds-wpml-mods/resources/js/build ./wp-content/plugins/cds-wpml-mods/resources/js/build

# Create a writable debug.log
RUN touch ./wp-content/debug.log \
    && chmod 660 ./wp-content/debug.log \
    && chown www-data:www-data ./wp-content/debug.log

VOLUME /usr/src/wordpress

USER www-data

EXPOSE 9000