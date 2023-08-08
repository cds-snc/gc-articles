# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.2.2-php8.1-fpm-alpine@sha256:1b2a3572f2fbe26487c2c660559a0883238f11a1cd26e6f85ebc5d9391865853

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov