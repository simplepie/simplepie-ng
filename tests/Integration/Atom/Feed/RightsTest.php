<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use GuzzleHttp\Psr7;
use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class RightsTest extends AbstractTestCase
{
    public function testRightsWithEntities(): void
    {
        $rights = $this->feed->getRights();

        $this->assertEquals(Node::class, Types::getClassOrType($rights));
        $this->assertEquals('Copyright © 2017 GitHub', (string) $rights);
        $this->assertEquals('Copyright © 2017 GitHub', $rights->getValue());
        $this->assertEquals(Serialization::TEXT, $rights->getSerialization());
    }

    public function testRightsWithoutEntities(): void
    {
        $stream = Psr7\stream_for($this->goodAtom);
        $parser = $this->simplepie->parseXml($stream, false);
        $feed   = $parser->getFeed();
        $rights = $feed->getRights();

        $this->assertEquals(Node::class, Types::getClassOrType($rights));
        $this->assertEquals('Copyright  2017 GitHub', (string) $rights);
        $this->assertEquals('Copyright  2017 GitHub', $rights->getValue());
        $this->assertEquals(Serialization::TEXT, $rights->getSerialization());
    }
}
