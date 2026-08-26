# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.1.0-php8.4-fpm-alpine@sha256:dd3cfa9b12df82a3bf3e8df811cb79d5d2d9239dd62c6ed671116c433654bca3

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov