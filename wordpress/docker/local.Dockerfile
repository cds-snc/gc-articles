# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.6.1-php8.1-fpm-alpine@sha256:32457b771713be7a03b291a3bcc310fef984c6c63375ff11027d8ebeace85d79

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov