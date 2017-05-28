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
    public const LOGGER_NOT_PSR3  = 'The configured logger MUST be compatible with `Psr\Log\LoggerInterface`. Received `%s` instead.';
}
