# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.7.2-php8.1-fpm-alpine@sha256:d6cfdc7875479aacfd158658b2c1b5671cb4e3e92b9c2e799016dabc4139daa1

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov