# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.3.1-php8.1-fpm-alpine@sha256:02a1a52a067fa416311d3093069b4f2184ec01b37c2399d40e58864c30ffd276

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov