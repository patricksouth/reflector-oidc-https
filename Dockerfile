# PHP image source.
# https://github.com/docker-library/docs/blob/master/php/README.md

FROM php:8.4-apache

RUN apt update && apt dist-upgrade -y && \
  apt install -y \
  curl \
  libjansson4 \
  wget \
  libhiredis0.14 \
  libcjose0 \
  libapache2-mod-auth-openidc \
  cron \
  logrotate && \
  rm -rf /var/lib/apt/lists/* && \
  touch /etc/apache2/sites-available/oidc-apache-site.conf

COPY src/ssl.conf /etc/apache2/mods-available/ssl.conf

RUN a2enmod rewrite && \
    a2enmod auth_openidc && \
    a2enmod socache_shmcb && \
    a2enmod ssl && \
    a2enmod headers && \
    a2dismod status && \
    a2dissite 000-default  && \
    a2ensite oidc-apache-site && \
    service apache2 restart

EXPOSE 80 443

CMD ["apache2-foreground"]
