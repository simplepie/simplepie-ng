<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Enum;

/**
 * Provides a set of known, allowable feed types. This is most often used for
 * determining which feed types a particular middleware should apply to.
 */
class FeedType extends AbstractEnum
{
    public const ALL = 'all';

    public const JSON = 'json';

    public const HTML = 'html';

    public const XML = 'xml';
}
