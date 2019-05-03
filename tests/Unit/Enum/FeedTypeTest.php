<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Enum;

use SimplePie\Enum\FeedType;
use SimplePie\Test\Unit\AbstractTestCase;

class FeedTypeTest extends AbstractTestCase
{
    public function testIntrospect(): void
    {
        static::assertSame(FeedType::introspect(), [
            'ALL'  => 'all',
            'JSON' => 'json',
            'HTML' => 'html',
            'XML'  => 'xml',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        static::assertSame(FeedType::introspectKeys(), [
            'ALL',
            'JSON',
            'HTML',
            'XML',
        ]);
    }

    public function testHasValue(): void
    {
        static::assertTrue(FeedType::hasValue(FeedType::ALL));
        static::assertTrue(FeedType::hasValue(FeedType::JSON));
        static::assertTrue(FeedType::hasValue(FeedType::HTML));
        static::assertTrue(FeedType::hasValue(FeedType::XML));

        static::assertFalse(FeedType::hasValue('nope'));
    }
}
