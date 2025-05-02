# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.8.1-php8.1-fpm-alpine@sha256:80133d7ca934fa288e4055cb4960d9edd592dad096099e2f7397f205af63a8c3

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov