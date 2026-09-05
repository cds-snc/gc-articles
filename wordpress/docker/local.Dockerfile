# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.1.0-php8.4-fpm-alpine@sha256:701e8b78e6a02d2d2cefe4917b19987c7196abfc84c37a8ebdff8cdf2eeeadba

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov