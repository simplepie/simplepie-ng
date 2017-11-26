# Installation for Ubuntu

Tested on Ubuntu 17.10.

## Installing PHP

```bash
sudo apt-get -y install \
    php7.1-cli \
    php7.1-curl \
    php7.1-intl \
    php7.1-opcache \
    php7.1-mbstring \
    php7.1-xml \
    php7.1-zip \
;
```

## Installing Composer

```bash
curl -S https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer
```

## Installing Dependencies

```bash
composer install -oa
```
