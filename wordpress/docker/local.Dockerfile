# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.2.0-php8.1-fpm-alpine@sha256:90d344b593fd500ca268da26abfdde8882cfec8d0a81bb5d541a293e50d80dd2

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov