# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.1.1-php8.1-fpm-alpine@sha256:4a1960d8baf6d080ecb0abb3c041a1813d206e1cf441f312ab153a58c0e7b4a1

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov