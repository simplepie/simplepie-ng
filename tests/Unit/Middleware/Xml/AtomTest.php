<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Middleware\Xml;

use SimplePie\Middleware\Xml\Atom;
use SimplePie\Test\Unit\AbstractTestCase;

class AtomTest extends AbstractTestCase
{
    public function testFailMe(): void
    {
        new Atom();
    }
}
