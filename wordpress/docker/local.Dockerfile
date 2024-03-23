# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.4.3-php8.1-fpm-alpine@sha256:e0ecf48d62ba5f32fbde56b44eb26a61f74ad0531ae0129150839388097b0541

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov