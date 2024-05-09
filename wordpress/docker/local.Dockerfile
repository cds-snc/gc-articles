# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.5.3-php8.1-fpm-alpine@sha256:7c1e5c32dadc194cdd7eb0dc0306b8345114120794c6dfe9b55f57d5400ef522

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov