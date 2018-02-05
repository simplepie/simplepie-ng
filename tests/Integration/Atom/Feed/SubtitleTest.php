<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class SubtitleTest extends AbstractTestCase
{
    public function testSubtitle(): void
    {
        $subtitle = $this->feed->getSubtitle();

        $this->assertEquals(Node::class, Types::getClassOrType($subtitle));
        $this->assertEquals('testing subtitles', (string) $subtitle);
        $this->assertEquals('testing subtitles', $subtitle->getValue());
        $this->assertEquals(Serialization::TEXT, $subtitle->getSerialization());
    }

    public function testSubtitleAtom10(): void
    {
        $subtitle = $this->feed->getSubtitle('atom10');

        $this->assertEquals(Node::class, Types::getClassOrType($subtitle));
        $this->assertEquals('testing subtitles', (string) $subtitle);
        $this->assertEquals('testing subtitles', $subtitle->getValue());
        $this->assertEquals(Serialization::TEXT, $subtitle->getSerialization());
    }
}
