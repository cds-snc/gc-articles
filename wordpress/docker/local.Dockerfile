# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.1.5-php8.4-fpm-alpine@sha256:39bf004fb34d16da75b5abe4d057b3d8a08e067839cdd74ccdf8f2cc158aac25

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov