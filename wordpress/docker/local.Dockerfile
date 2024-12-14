# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.7.1-php8.1-fpm-alpine@sha256:f5bd1018e9b171882a03f9ef46ffa630f355324a104ea588fbfcb1beb6966256

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov