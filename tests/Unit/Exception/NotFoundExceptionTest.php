<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Unit\Exception;

use SimplePie\Exception\NotFoundException;
use SimplePie\Test\Unit\AbstractTestCase;

class NotFoundExceptionTest extends AbstractTestCase
{
    /**
     * @expectedException \SimplePie\Exception\NotFoundException
     * @expectedExceptionMessage This is a test message.
     */
    public function testThrow()
    {
        throw new NotFoundException('This is a test message.');
    }
}
