<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Enum;

use DateTime;

/**
 * Provides a set of commonly-used time/date-stamps in feeds.
 */
class DateFormat extends AbstractEnum
{
    // Y-m-d\TH:i:sP
    public const ATOM = DateTime::RFC3339;

    public const ISO8601 = DateTime::RFC3339;

    public const RFC3339 = DateTime::RFC3339;

    // D, d M y H:i:s O
    public const RFC822 = DateTime::RFC822;

    // D, d M Y H:i:s O
    public const RFC2822 = DateTime::RFC2822;

    public const RSS20 = DateTime::RFC2822;
}
