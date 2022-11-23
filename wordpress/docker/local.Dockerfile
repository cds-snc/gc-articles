# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.1.1-php8.1-fpm-alpine@sha256:50d6cd2b892b28ac33ed3de47aae062d7d45fa1233d8ce32c41338e772e0d017

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov