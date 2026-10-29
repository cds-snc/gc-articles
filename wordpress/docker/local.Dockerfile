# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.1.4-php8.4-fpm-alpine@sha256:12a817b8b143f436c99319555cef58024a856f11ccae0b989b6329445b1a6e03

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov