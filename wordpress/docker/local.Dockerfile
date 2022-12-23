# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.1.1-php8.1-fpm-alpine@sha256:f0a7d232fbeac8df2962fad9f33c6d93a156c5ed18571bc1803a1dc7d17c92ca

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov