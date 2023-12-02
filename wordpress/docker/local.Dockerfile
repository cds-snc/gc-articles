# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.4.1-php8.1-fpm-alpine@sha256:4a53e7e7fe2b55b9768d6a47ed2fec83f8905a9724fb5d777e93e455d80c43c5

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov