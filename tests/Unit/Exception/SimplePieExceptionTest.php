<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Exception;

use SimplePie\Exception\SimplePieException;
use SimplePie\Test\Unit\AbstractTestCase;

class SimplePieExceptionTest extends AbstractTestCase
{
    public function testThrow(): void
    {
        $this->expectException(\SimplePie\Exception\SimplePieException::class);
        $this->expectExceptionMessage('This is a test message.');

        throw new SimplePieException('This is a test message.');
    }
}
