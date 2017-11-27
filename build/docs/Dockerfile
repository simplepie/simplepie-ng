FROM skyzyx/alpine-pandoc:1.0.0

ENV PERSISTENT_DEPS wget git mercurial make gmp openssh
ENV SPHINXBUILD /usr/bin/sphinx-build
ENV SPHINXOPTS -T

# Copy Source code and set working directory
COPY docs /var/docs
WORKDIR /var/docs

USER root

RUN apk add --no-cache --virtual .persistent-deps $PERSISTENT_DEPS && \
    pip install -r requirements.txt

ENTRYPOINT ["make", "docs"]
