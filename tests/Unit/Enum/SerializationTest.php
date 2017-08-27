<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Enum;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Unit\AbstractTestCase;

class SerializationTest extends AbstractTestCase
{
    public function testIntrospect(): void
    {
        $this->assertSame(Serialization::introspect(), [
            'TEXT'  => 'text',
            'HTML'  => 'html',
            'XHTML' => 'xhtml',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        $this->assertSame(Serialization::introspectKeys(), [
            'TEXT',
            'HTML',
            'XHTML',
        ]);
    }

    public function testHasValue(): void
    {
        $this->assertTrue(Serialization::hasValue(Serialization::TEXT));
        $this->assertTrue(Serialization::hasValue(Serialization::HTML));
        $this->assertTrue(Serialization::hasValue(Serialization::XHTML));

        $this->assertFalse(Serialization::hasValue('nope'));
    }
}
