<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class SummaryTest extends AbstractTestCase
{
    public function testSummary(): void
    {
        $summary = $this->feed->getSummary();

        static::assertEquals(Node::class, Types::getClassOrType($summary));
        static::assertEquals('This is a bunch of CDATA! 😀 [&#8230;]', (string) $summary);
        static::assertEquals('This is a bunch of CDATA! 😀 [&#8230;]', $summary->getValue());
        static::assertEquals(Serialization::HTML, $summary->getSerialization());
    }

    public function testSummaryAtom10(): void
    {
        $summary = $this->feed->getSummary('atom10');

        static::assertEquals(Node::class, Types::getClassOrType($summary));
        static::assertEquals('This is a bunch of CDATA! 😀 [&#8230;]', (string) $summary);
        static::assertEquals('This is a bunch of CDATA! 😀 [&#8230;]', $summary->getValue());
        static::assertEquals(Serialization::HTML, $summary->getSerialization());
    }
}
