<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
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
        static::assertSame(Serialization::introspect(), [
            'TEXT'  => 'text',
            'HTML'  => 'html',
            'XHTML' => 'xhtml',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        static::assertSame(Serialization::introspectKeys(), [
            'TEXT',
            'HTML',
            'XHTML',
        ]);
    }

    public function testHasValue(): void
    {
        static::assertTrue(Serialization::hasValue(Serialization::TEXT));
        static::assertTrue(Serialization::hasValue(Serialization::HTML));
        static::assertTrue(Serialization::hasValue(Serialization::XHTML));

        static::assertFalse(Serialization::hasValue('nope'));
    }
}
