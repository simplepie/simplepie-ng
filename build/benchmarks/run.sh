#! /usr/bin/env sh
php -i | grep -i "^opcache\."
echo "-------------------------------------------------------------------------"
php /workspace/tests/benchmarks/atom10.php 50
