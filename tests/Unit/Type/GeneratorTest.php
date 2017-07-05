<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Unit\Type;

use SimplePie\Type\Generator;
use SimplePie\Test\Unit\AbstractTestCase;

class GeneratorTest extends AbstractTestCase
{
    public function testFailMe()
    {
        new Generator();
    }
}
