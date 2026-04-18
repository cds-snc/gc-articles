# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.9.4-php8.4-fpm-alpine@sha256:b4617ae62504530ac3596231add30ae9dc7559d2f481c8dfc8fc3e1a8da62860

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk upgrade --no-cache imagemagick imagemagick-webp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov