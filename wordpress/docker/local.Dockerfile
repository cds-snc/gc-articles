# wordpress version needs to match the version found in ~/wordpress/docker/Dockerfile
FROM wordpress:6.5.5-php8.1-fpm-alpine@sha256:740784c6b585bb51faaf59cc02443705b04f65fb3dfa4e0cefb3af920f512c11

WORKDIR /usr/src/wordpress

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov