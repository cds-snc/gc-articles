# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.6.2-php8.1-fpm-alpine@sha256:1d2de28f48f1ed40be8cc00ffab327a817139d72eaa6f70bed86bfe776e10cda

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov