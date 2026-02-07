# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.9.1-php8.4-fpm-alpine@sha256:2b32b52b9093b7d7da1028547832892a96912c26a59eee5d854bd3a729e66b44

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov