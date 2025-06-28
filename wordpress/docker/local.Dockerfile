# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.8.1-php8.1-fpm-alpine@sha256:4ef355a060cc73131a3a0592c242fe92ba6f36ff37fa5418e52b3bbedbcfe74b

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov