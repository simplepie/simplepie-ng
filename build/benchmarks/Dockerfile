FROM php:7.2.0-cli-alpine3.7

ENV BUILD_DEPS alpine-sdk curl-dev icu-dev libxml2-dev
ENV PERSISTENT_DEPS curl icu libxslt-dev
ENV INSTALL_EXTENSIONS curl intl json opcache mbstring xml xsl zip

# Install Packages
RUN apk upgrade --update && \
    apk add --no-cache --virtual .build-deps $BUILD_DEPS && \
    apk add --no-cache --virtual .persistent-deps $PERSISTENT_DEPS && \
    docker-php-ext-install $INSTALL_EXTENSIONS && \
    apk del .build-deps

# Copy PHP Config
COPY src /var/simplepie-ng/src
COPY tests /var/simplepie-ng/tests
COPY vendor /var/simplepie-ng/vendor
COPY build/benchmarks/php.ini /usr/local/etc/php/php.ini
COPY build/benchmarks/run.sh /tmp/run.sh
RUN chmod +x /tmp/run.sh

# Set To non root user
USER www-data

WORKDIR /var/simplepie-ng

ENTRYPOINT ["/tmp/run.sh"]
