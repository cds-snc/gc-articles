# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.4.3-php8.1-fpm-alpine@sha256:2c51ee772ac0731d83c5e1d8aacd22365fc521c2a120ff029068dea0d84dbed8

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov