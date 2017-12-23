<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Exception;

use SimplePie\Exception\ConfigurationException;
use SimplePie\Test\Unit\AbstractTestCase;

class ConfigurationExceptionTest extends AbstractTestCase
{
    public function testThrow(): void
    {
        $this->expectException(\SimplePie\Exception\ConfigurationException::class);
        $this->expectExceptionMessage('This is a test message.');

        throw new ConfigurationException('This is a test message.');
    }
}
