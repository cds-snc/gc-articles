# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.7.1-php8.1-fpm-alpine@sha256:8853052e0c5f690b03ba5a148d272e82ac8977e2c3d359715c44252abaf40e13

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov