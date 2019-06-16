<?php
/**
 * Copyright (c) 2010–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2016–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

if (!\function_exists('xdebug_set_filter')) {
    return;
}

xdebug_set_filter(
    \XDEBUG_FILTER_CODE_COVERAGE,
    \XDEBUG_PATH_WHITELIST,
    [
        \dirname(__DIR__) . '/src',
    ]
);
