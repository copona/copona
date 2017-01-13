FROM webdevops/php-apache-dev:centos-7-php56
EXPOSE 80
RUN echo display_errors = On >> /opt/docker/etc/php/php.ini
RUN echo error_reporting = E_ALL >> /opt/docker/etc/php/php.ini
RUN echo display_startup_errors = On >> /opt/docker/etc/php/php.ini
RUN mkdir /var/lib/php/session && chmod 777 /var/lib/php/session