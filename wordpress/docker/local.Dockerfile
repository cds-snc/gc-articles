# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.2.2-php8.1-fpm-alpine@sha256:bd635599df02254274bbee5ff8de6eeafae61f880b8fb06513b448d2ce19573f

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov