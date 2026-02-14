# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.9.1-php8.4-fpm-alpine@sha256:0d46d34def7e5fecbeabdde06e0ac64914f210093695b04bf17f90ae42688530

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov