# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.0.2-php8.4-fpm-alpine@sha256:a0bb47ed4a9a98835f6bc4c2b63f0167402f3bb47d2c77248238ab98a7f1029a

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov