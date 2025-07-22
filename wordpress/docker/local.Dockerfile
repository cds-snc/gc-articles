# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.8.2-php8.1-fpm-alpine@sha256:2021a18ca3f7ff312e676b5c44e5393218a064fb6ae8261a8fa09e5ef06c67ec

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov