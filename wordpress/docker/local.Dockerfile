# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.0.0-php8.4-fpm-alpine@sha256:5c60b9119d00de8a30ec629e3f0ed2178bfc035dda559d90e27c39419ddba947

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov