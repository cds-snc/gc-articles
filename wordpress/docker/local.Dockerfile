# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.3.1-php8.1-fpm-alpine@sha256:d889814813c8722a204017565dcc6068b24da870acf41dbb42c2501a84a16e14

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov