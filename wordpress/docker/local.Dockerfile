# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.1.1-php8.1-fpm-alpine@sha256:d211f37e60c263363fea457024972053e01015ed841ad4d1a60c14a9cafa1e13

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov