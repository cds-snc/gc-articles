# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.7.2-php8.1-fpm-alpine@sha256:b1e5d29df9967367b7cf04ebb523bba5aff36da4781703f5fc6f64556747540b

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov