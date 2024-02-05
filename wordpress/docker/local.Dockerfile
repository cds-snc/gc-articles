# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.4.3-php8.1-fpm-alpine@sha256:e5613df0b982b61acf5be992a28a414895ec56ef6ed5dbf705bdb4d46d76c8b7

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov