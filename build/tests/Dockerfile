FROM php:7.2.0RC6-cli-alpine3.6

ENV BUILD_DEPS alpine-sdk curl-dev icu-dev libxml2-dev
ENV PERSISTENT_DEPS curl icu make php7-xdebug
ENV INSTALL_EXTENSIONS curl intl json opcache mbstring xml zip

# Install Packages
RUN echo "http://dl-cdn.alpinelinux.org/alpine/v3.6/community" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.6/main" >> /etc/apk/repositories && \
    apk upgrade --update && \
    apk add --no-cache --virtual .build-deps $BUILD_DEPS && \
    apk add --no-cache --virtual .persistent-deps $PERSISTENT_DEPS && \
    docker-php-ext-install $INSTALL_EXTENSIONS && \
    apk del .build-deps

# Copy PHP Config
COPY build/tests/php.ini /usr/local/etc/php/php.ini

# Set To non root user
USER www-data

WORKDIR /var/simplepie-ng

ENTRYPOINT ["make", "test"]
