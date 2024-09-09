# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.6.1-php8.1-fpm-alpine@sha256:1fe2d4a9b77e259434f7c030898cb4705d6cb93509b8a43ccb7d4f97fc3def93

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov