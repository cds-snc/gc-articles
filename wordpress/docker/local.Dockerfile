# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.4.2-php8.1-fpm-alpine@sha256:73f8033d2109bc2a32a20302cdf4791dc75fde4723d6f362e360233d5305507b

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov