FROM devilbox/php-fpm:8.2-work as dev
COPY xdebug.ini /etc/php-custom.d/xdebug.ini
COPY memory_limit.ini /etc/php-custom.d/memory_limit.ini

CMD cd /var/www
CMD chown -R 777 webprod.com/var
CMD chown -R www-data:www-data webprod.com/var
CMD chown -R 777 webprod.com/var/log
CMD chown -R www-data:www-data webprod.com/var/log

WORKDIR /var/www/webprod.com

##########################################
# This Dockerfile is used for building an image that is used to run test and lint within the pull_request CI pipeline
##########################################
ARG FLAVOR
FROM segment/chamber:2 AS chamber


FROM php:fpm-alpine
# Installs `chamber` for secure access to AWS SSM Parameter Store

COPY --from=chamber /chamber /bin/chamber

# Install packages
RUN apk update && apk upgrade
RUN apk --no-cache add \
    bash \
    nginx \
    gcc \
    make \
    autoconf \
    tini \
    openssh \
    git \
    gcc \
    make \
    autoconf \
    curl \
    wget \
    zip

# Install PHP Extensions
RUN apk --no-cache add \
    php81-pecl-apcu \
    php81-pear

RUN docker-php-ext-install mysqli pdo pdo_mysql

ENV PHPREDIS_VERSION 5.3.7
ADD https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz /tmp/redis.tar.gz
RUN tar xzf /tmp/redis.tar.gz -C /tmp \
    && mkdir -p /usr/src/php/ext \
    && mv /tmp/phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis \
    && rm -rf /usr/src/php/ext/redis

# Install composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure nginx
COPY docker/config/nginx/conf.d/stage.conf /etc/nginx/http.d/default.conf

# Configure PHP
COPY docker/config/php/fpm/conf.d/20-limits.ini /etc/php/conf.d/20-limits.ini
COPY docker/config/php/fpm/pool.d/www.conf /etc/php/php-fpm.d/www.conf

ARG DATADOG_API_KEY

RUN DD_AGENT_MAJOR_VERSION=7 DD_API_KEY=$DATADOG_API_KEY DD_SITE="datadoghq.com" bash -c "$(curl -L https://s3.amazonaws.com/dd-agent/scripts/install_script.sh)" || true

## Install latest version of datadog tracer
RUN curl -s https://api.github.com/repos/DataDog/dd-trace-php/releases/latest | grep "browser_download_url.*apk" | head -1 | cut -d / -f 8,8 > DATADOG_VERSION \
    && wget https://github.com/DataDog/dd-trace-php/releases/download/$(cat DATADOG_VERSION)/datadog-php-tracer_$(cat DATADOG_VERSION)_$(uname -m).apk \
    && apk add --no-cache --allow-untrusted datadog-php-tracer_$(cat DATADOG_VERSION)_$(uname -m).apk \
    && rm datadog-php-tracer_$(cat DATADOG_VERSION)_$(uname -m).apk

RUN echo "extension=/opt/datadog-php/extensions/ddtrace-20220829-alpine.so" > /usr/local/etc/php/conf.d/docker-php-ext-ddtrace.ini \
    && echo "datadog.trace.request_init_hook=/opt/datadog-php/dd-trace-sources/bridge/dd_wrap_autoloader.php" >> /usr/local/etc/php/conf.d/docker-php-ext-ddtrace.ini

# Copy Source
COPY webprod.com /var/www/html
COPY docker/dist.env.loc /tmp/dist.env.loc

# Copy Entrypoint
COPY docker/entrypoint.sh /usr/bin/entrypoint
RUN chmod +x /usr/bin/entrypoint

# Set permissions
WORKDIR /var/www
RUN chmod -R 755 html
#RUN chown -R www-data:www-data html
RUN mkdir -p /var/run/php
RUN chmod -R 777 /var/run/php
RUN mkdir -p /var/www/html/var/cache/dev/profiler
RUN chmod -R 777 /var/www/html/var

# Symlink errors to go to sdtoout
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

WORKDIR /var/www/webprod.com

EXPOSE 80

STOPSIGNAL SIGTERM

ENTRYPOINT /usr/bin/entrypoint