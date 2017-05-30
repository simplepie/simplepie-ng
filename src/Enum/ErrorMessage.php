<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

class ErrorMessage extends AbstractEnum
{
    public const LOGGER_NOT_PSR3 = 'The configured logger MUST be compatible with `Psr\Log\LoggerInterface`. '
        . 'Received `%s` instead.';

    public const DOM_NOT_EXTEND_FROM = 'The `DOM%s` class could not be extended by `%s` because it does not extend '
        . 'the `SimplePie\Dom\%s` class.';
}
