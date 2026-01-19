# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.9.0-php8.4-fpm-alpine@sha256:bda6874817f833a66b3ee1407a7b1f77971a70404b99f3de96dfa1e7d970eae5

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov