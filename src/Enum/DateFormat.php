<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
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
    public const ATOM = DateTime::RFC3339;

    public const ISO8601 = DateTime::RFC3339;

    public const RFC3339 = DateTime::RFC3339;

    public const RFC822 = DateTime::RFC822;

    public const RFC2822 = DateTime::RFC2822;

    public const RSS20 = DateTime::RFC2822;

    public const LONG_12HOUR = 'l, jS F Y, g:i a T';

    public const LONG_24HOUR = 'l, jS F Y, G:i a T';

    public const SHORT_12HOUR = 'D, j M Y, g:i a T';

    public const SHORT_24HOUR = 'D, j M Y, G:i a T';
}
