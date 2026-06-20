# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.0.0-php8.4-fpm-alpine@sha256:1491f5a4177a34b8fd84cdd99821ec18b2616aa85841a5490c6d70802f026142

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov