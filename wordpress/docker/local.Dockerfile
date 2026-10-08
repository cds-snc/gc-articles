# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.1.3-php8.4-fpm-alpine@sha256:b0aadf3fdbf5a787a5fffe5f580c54d8612e8c8c0d30f4e539cd842d2bd4ad2e

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov