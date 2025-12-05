# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.9.0-php8.4-fpm-alpine@sha256:ce1c71ebbaed797644c0fcfe5e23c4afcacf68975289951a0967611835919dca

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov