<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
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
        $this->assertSame(FeedType::introspect(), [
            'ALL'  => 'all',
            'JSON' => 'json',
            'HTML' => 'html',
            'XML'  => 'xml',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        $this->assertSame(FeedType::introspectKeys(), [
            'ALL',
            'JSON',
            'HTML',
            'XML',
        ]);
    }

    public function testHasValue(): void
    {
        $this->assertTrue(FeedType::hasValue(FeedType::ALL));
        $this->assertTrue(FeedType::hasValue(FeedType::JSON));
        $this->assertTrue(FeedType::hasValue(FeedType::HTML));
        $this->assertTrue(FeedType::hasValue(FeedType::XML));

        $this->assertFalse(FeedType::hasValue('nope'));
    }
}
