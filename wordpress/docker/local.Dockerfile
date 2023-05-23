# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.2.2-php8.1-fpm-alpine@sha256:2103213d427293516a3e4998bdce766416be2d5b1410daaee30f7856ffa98e54

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov