# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.8.3-php8.1-fpm-alpine@sha256:e005fe00aa956d2a05e5310699fe111c829a1d0421acd7644938ccb604297548

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov