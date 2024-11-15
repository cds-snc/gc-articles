# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.7.0-php8.1-fpm-alpine@sha256:7e310fe1da19c61fafb3fadf00ffdae5916165115b84f439b776907c37982664

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov