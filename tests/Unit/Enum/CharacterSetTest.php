<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Enum;

use SimplePie\Enum\CharacterSet;
use SimplePie\Test\Unit\AbstractTestCase;

class CharacterSetTest extends AbstractTestCase
{
    public function testIntrospect(): void
    {
        $this->assertSame(CharacterSet::introspect(), [
            'ISO_8859_1' => 'iso-8859-1',
            'US_ASCII'   => 'us-ascii',
            'UTF_8'      => 'utf-8',
            'WIN_1252'   => 'windows-1252',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        $this->assertSame(CharacterSet::introspectKeys(), [
            'ISO_8859_1',
            'US_ASCII',
            'UTF_8',
            'WIN_1252',
        ]);
    }

    public function testHasValue(): void
    {
        $this->assertTrue(CharacterSet::hasValue(CharacterSet::ISO_8859_1));
        $this->assertTrue(CharacterSet::hasValue(CharacterSet::US_ASCII));
        $this->assertTrue(CharacterSet::hasValue(CharacterSet::UTF_8));
        $this->assertTrue(CharacterSet::hasValue(CharacterSet::WIN_1252));

        $this->assertFalse(CharacterSet::hasValue('nope'));
    }
}
