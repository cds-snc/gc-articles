# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.5.0-php8.1-fpm-alpine@sha256:07dfc2a769bbb5cd8e2327b254a59f5a5aae1d3537725e3c554d2881968a5fa2

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov