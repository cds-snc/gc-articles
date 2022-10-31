FROM wordpress:6.0.3-php8.1-fpm-alpine@sha256:b325e13d26dfed641743b3733ef0bb65be9162f4338b0d690bbb10e95ed88291

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov