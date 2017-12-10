<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Middleware\Xml;

use SimplePie\Middleware\Xml\Rss;
use SimplePie\Test\Unit\AbstractTestCase;

/**
 * @coversNothing
 */
class RssTest extends AbstractTestCase
{
    public function testFailMe(): void
    {
        new Rss();
    }
}
