# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.8.3-php8.1-fpm-alpine@sha256:24260c29b8772aed84d4fed2fcbb3ccc6df24b21b9423e94be6cc1e4a4d600a2

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov