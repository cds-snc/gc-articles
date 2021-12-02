FROM httpd:2.4-alpine

WORKDIR /usr/src/wordpress

# Setup self-signed cert/ssl
ARG APACHE_CERT
ARG APACHE_KEY

RUN echo "$APACHE_CERT" > /usr/local/apache2/conf/server.crt \
    && echo "$APACHE_KEY" > /usr/local/apache2/conf/server.key

# Copy config
COPY ./wordpress/docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf
# COPY ./wordpress/docker/apache/conf.d/php-fpm.conf /usr/local/apache2/conf.d/php-fpm.conf
COPY ./wordpress/docker/apache/conf.d/default-site-ssl.conf /usr/local/apache2/conf.d/default-site-ssl.conf
COPY ./wordpress/docker/apache/index.html /usr/src/wordpress/index.html


# Configure SSL
RUN sed -i \
        -e 's/^#\(Include .*httpd-ssl.conf\)/\1/' \
        -e 's/^#\(LoadModule .*mod_ssl.so\)/\1/' \
        -e 's/^#\(LoadModule .*mod_socache_shmcb.so\)/\1/' \
        /usr/local/apache2/conf/httpd.conf

EXPOSE 80 443
