FROM webdevops/php-apache-dev:8.3
EXPOSE 80
RUN echo display_errors = On >> /opt/docker/etc/php/php.ini
RUN echo error_reporting = E_ALL >> /opt/docker/etc/php/php.ini
RUN echo display_startup_errors = On >> /opt/docker/etc/php/php.ini
RUN mkdir -p /var/lib/php/session && chmod 777 /var/lib/php/session
# Create storage dirs at container startup (runs after volume mount so host dirs get the right perms)
RUN printf '#!/bin/sh\nmkdir -p \\\n  /app/storage/public/cache/image \\\n  /app/storage/public/cache \\\n  /app/storage/private/cache \\\n  /app/storage/private/logs \\\n  /app/storage/private/download \\\n  /app/storage/private/upload \\\n  /app/storage/private/modification\nchmod -R 777 /app/storage\nfind /app/storage -type f -exec chmod 666 {} +\n' \
    > /opt/docker/provision/entrypoint.d/10-storage-init.sh \
    && chmod +x /opt/docker/provision/entrypoint.d/10-storage-init.sh

# xdebug 3.x config (xdebug 2.x ini keys removed in xdebug 3)
RUN echo "xdebug.mode = off" >> /opt/docker/etc/php/php.ini
RUN echo "xdebug.idekey = PHPSTORM" >> /opt/docker/etc/php/php.ini
RUN echo "xdebug.start_with_request = yes" >> /opt/docker/etc/php/php.ini
RUN echo "xdebug.discover_client_host = false" >> /opt/docker/etc/php/php.ini
RUN echo "xdebug.client_host = 10.254.254.254" >> /opt/docker/etc/php/php.ini