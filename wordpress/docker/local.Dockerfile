# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.0.2-php8.4-fpm-alpine@sha256:9c5ea2aceb5948ae3c925b21ee3715b5d5779d17beed1e645de901d66c694c7f

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov