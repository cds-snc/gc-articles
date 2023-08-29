# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.3.0-php8.1-fpm-alpine@sha256:f703f6525b214e8f69b03bffa786011b294be56b57a115ecefe1585a4db96e7c

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov