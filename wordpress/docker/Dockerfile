FROM wordpress:5.8.1-php8.0-apache

# Add build deps
RUN apk add --update --virtual mod-deps  \
    autoconf  \
    alpine-sdk \
    libmcrypt-dev

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN pecl install pcov \
    && docker-php-ext-enable pcov

# clean up
RUN apk del mod-deps && \
  rm -rf /apk /tmp/* /var/cache/apk/*