# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.5.5-php8.1-fpm-alpine@sha256:4cb01d13f5ab4b473b50f3e91a4e8bb7ce7c9da3a11a7cf46b2c817e8fea1d9e

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov