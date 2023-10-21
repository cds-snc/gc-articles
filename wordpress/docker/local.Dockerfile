# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.3.2-php8.1-fpm-alpine@sha256:afdc75a2e0e8d523447d1b550920999be0e5834cc1d4a43c5840563e84d01673

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov