# Production-style image for the public demo store: the same webdevops base
# as the dev image, but without xdebug and with errors logged, not shown.
FROM webdevops/php-apache:8.3
EXPOSE 80
RUN { echo 'display_errors = Off'; echo 'display_startup_errors = Off'; echo 'log_errors = On'; echo 'expose_php = Off'; } >> /opt/docker/etc/php/php.ini
RUN printf '#!/bin/sh\nmkdir -p /app/storage/public/cache/image /app/storage/private/cache /app/storage/private/logs /app/storage/private/download /app/storage/private/upload /app/storage/private/modification\nchown -R application:application /app/storage\n' \
    > /opt/docker/provision/entrypoint.d/10-storage-init.sh \
    && chmod +x /opt/docker/provision/entrypoint.d/10-storage-init.sh
