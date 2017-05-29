FROM webdevops/php-apache-dev:centos-7-php56
EXPOSE 80
RUN echo display_errors = On >> /opt/docker/etc/php/php.ini
RUN echo error_reporting = E_ALL >> /opt/docker/etc/php/php.ini
RUN echo display_startup_errors = On >> /opt/docker/etc/php/php.ini
RUN echo always_populate_raw_post_data = -1 >> /opt/docker/etc/php/php.ini
RUN mkdir -p /var/lib/php/session && chmod 777 /var/lib/php/session

# xdebug
RUN echo xdebug.profiler_enable = 1 >> /opt/docker/etc/php/php.ini
RUN echo xdebug.profiler_output_dir = /log/xdebug >> /opt/docker/etc/php/php.ini
RUN echo xdebug.ide_key >> /opt/docker/etc/php/php.ini