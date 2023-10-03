# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.3.1-php8.1-fpm-alpine@sha256:8f227c350b78a556c0e1080ed4fe3505a1d6418538faf4374e478c92143147a9

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov