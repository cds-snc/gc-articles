# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:7.0.1-php8.4-fpm-alpine@sha256:29fdb128683527006459d3ccbf3f49e1b47314067562f3366c93299626a3f2db

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov