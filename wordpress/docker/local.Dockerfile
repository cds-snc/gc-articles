# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.2.0-php8.1-fpm-alpine@sha256:ea4996efd3d4035c9426a5b0fe2e9f9901dbd12240a8dc1a0574217487f0d7b0

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov