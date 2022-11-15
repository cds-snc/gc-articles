FROM httpd:2.4.54-alpine@sha256:4658c554fe215c8a17d57adbd9f8595e4922abda40d1e67a276456d2f142400e

WORKDIR /usr/src/wordpress

# Setup self-signed cert/ssl
ARG APACHE_CERT
ARG APACHE_KEY

RUN echo "$APACHE_CERT" > /usr/local/apache2/conf/server.crt \
    && echo "$APACHE_KEY" > /usr/local/apache2/conf/server.key

RUN apk update \
    && apk upgrade \
    && apk add libcap procps \
    && setcap 'cap_net_bind_service=+ep' /usr/local/apache2/bin/httpd \
    && chown www-data:www-data /usr/local/apache2/logs

# Copy config
COPY ./wordpress/docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./wordpress/docker/apache/conf.d/php-fpm.conf /usr/local/apache2/conf.d/php-fpm.conf
COPY ./wordpress/docker/apache/conf.d/default-site-ssl.conf /usr/local/apache2/conf.d/default-site-ssl.conf

# Configure php-fpm
RUN sed -i -e 's/wordpress/127.0.0.1/g' /usr/local/apache2/conf.d/php-fpm.conf

# Configure SSL
RUN sed -i \
        -e 's/^#\(LoadModule .*mod_ssl.so\)/\1/' \
        -e 's/^#\(LoadModule .*mod_socache_shmcb.so\)/\1/' \
        /usr/local/apache2/conf/httpd.conf

USER www-data

EXPOSE 80 443
