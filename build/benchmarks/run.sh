#! /usr/bin/env sh
php -i | grep -i "^opcache\."
echo "-------------------------------------------------------------------------"
php /var/simplepie-ng/tests/benchmarks/atom10.php 10
