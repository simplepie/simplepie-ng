FROM php:7.2-cli-alpine3.9

ENV BUILD_DEPS alpine-sdk curl-dev icu-dev libxml2-dev libxslt-dev libzip-dev autoconf
ENV PERSISTENT_DEPS curl icu libxslt libzip
ENV INSTALL_EXTENSIONS curl intl json opcache mbstring xml xsl zip
ENV INSTALL_PECL ds

# Install Packages
RUN apk upgrade --update
RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS
RUN apk add --no-cache --virtual .persistent-deps $PERSISTENT_DEPS
RUN docker-php-ext-install $INSTALL_EXTENSIONS
RUN pecl install $INSTALL_PECL && docker-php-ext-enable $INSTALL_PECL
RUN apk del .build-deps
RUN apk add --no-cache --virtual .persistent-deps $PERSISTENT_DEPS

WORKDIR /workspace
