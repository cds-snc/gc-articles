# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.6.2-php8.1-fpm-alpine@sha256:3501596c2e47e46884c7cc756423aab4c4fab85dca4760ab56fb50010942aefb

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov