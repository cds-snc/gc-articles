FROM httpd:2.4-alpine

# Update path to wordpress in apache *.conf files
RUN set -eux; \
    find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +;

# Setup self-signed cert/ssl
ARG APACHE_CERT
ARG APACHE_KEY

COPY ./wordpress/docker/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod ssl \
    && a2ensite default-ssl \
    && echo "$APACHE_CERT" > /etc/ssl/certs/self-signed.crt \
    && echo "$APACHE_KEY" > /etc/ssl/private/self-signed.key