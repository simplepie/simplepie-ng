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
    /**
     * 1970-01-01T00:00:00+00:00.
     */
    public const ATOM = DateTime::RFC3339;

    /**
     * 1970-01-01T00:00:00+00:00.
     */
    public const ISO8601 = DateTime::RFC3339;

    /**
     * 1970-01-01T00:00:00+00:00.
     */
    public const RFC3339 = DateTime::RFC3339;

    /**
     * Thu, 01 Jan 70 00:00:00 +0000.
     */
    public const RFC822 = DateTime::RFC822;

    /**
     * Thu, 01 Jan 1970 00:00:00 +0000.
     */
    public const RFC2822 = DateTime::RFC2822;

    /**
     * Thu, 01 Jan 1970 00:00:00 +0000.
     */
    public const RSS20 = DateTime::RFC2822;

    /**
     * Thursday, 1st January 1970, 12:00am GMT.
     */
    public const LONG_12HOUR = 'l, jS F Y, g:ia T';

    /**
     * Thursday, 1st January 1970, 0:00 GMT.
     */
    public const LONG_24HOUR = 'l, jS F Y, G:i T';

    /**
     * Thu, 1 Jan 1970, 12:00am GMT.
     */
    public const SHORT_12HOUR = 'D, j M Y, g:ia T';

    /**
     * Thu, 1 Jan 1970, 0:00 GMT.
     */
    public const SHORT_24HOUR = 'D, j M Y, G:i T';

    /**
     * 1514357702.
     */
    public const SINCE_EPOCH = 'U';
}
