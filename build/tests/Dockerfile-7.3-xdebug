FROM simplepieng/base:7.3

ENV BUILD_DEPS alpine-sdk curl-dev icu-dev libxml2-dev libxslt-dev git autoconf
ENV XDEBUG_VERSION 2.7.1

# Install Packages
RUN apk add --virtual .build-deps $BUILD_DEPS
RUN cd /tmp && \
	git clone git://github.com/xdebug/xdebug.git && \
	cd /tmp/xdebug && \
    git checkout ${XDEBUG_VERSION} && \
    sh ./rebuild.sh
RUN rm -Rf /tmp/xdebug
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini
RUN apk del .build-deps

# Keep make no matter what
RUN apk add make

# Copy PHP Config
COPY build/tests/php.ini /usr/local/etc/php/php.ini

WORKDIR /workspace
ENTRYPOINT ["make", "test"]
