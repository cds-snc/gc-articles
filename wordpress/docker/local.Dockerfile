# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.6.2-php8.1-fpm-alpine@sha256:10349ef8a85792f9c4d7b0c8781c3f514be8f33ade9c7377c4465c81fb0b51c7

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov