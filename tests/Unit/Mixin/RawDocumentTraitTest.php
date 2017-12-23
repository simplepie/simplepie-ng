<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Mixin;

use SimplePie\Mixin\RawDocumentTrait;
use SimplePie\Test\Unit\AbstractTestCase;

class RawDocumentTraitTest extends AbstractTestCase
{
    use RawDocumentTrait;

    public function testFailMe(): void
    {
    }
}
