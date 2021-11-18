FROM wordpress:5.8.2-php8.0-fpm-alpine

WORKDIR /usr/src/wordpress

RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    apache2-ssl \
    supervisor

COPY apache/httpd.conf /etc/apache2/httpd.conf
COPY include/supervisord.ini /etc/supervisor.d/supervisord.ini

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]