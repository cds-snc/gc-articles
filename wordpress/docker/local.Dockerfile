# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.5.3-php8.1-fpm-alpine@sha256:34eccfa8eff76767311eb6db5816b6b79e4f1906f8a869973272faa1620b3fa4

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov