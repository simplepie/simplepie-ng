<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Exception;

use SimplePie\Exception\MiddlewareException;
use SimplePie\Test\Unit\AbstractTestCase;

class MiddlewareExceptionTest extends AbstractTestCase
{
    /**
     * @expectedException \SimplePie\Exception\MiddlewareException
     * @expectedExceptionMessage This is a test message.
     */
    public function testThrow(): void
    {
        throw new MiddlewareException('This is a test message.');
    }
}
