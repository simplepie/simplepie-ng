<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Generator;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class GeneratorTest extends AbstractTestCase
{
    public function testGenerator(): void
    {
        $generator = $this->feed->getGenerator();

        $this->assertEquals(Generator::class, Types::getClassOrType($generator));
        $this->assertEquals('WordPress 4.7.5', (string) $generator);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($generator->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($generator->getName()));
        $this->assertEquals('WordPress', (string) $generator->getName());
        $this->assertEquals(Serialization::TEXT, $generator->getName()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($generator->getVersion()));
        $this->assertEquals('4.7.5', (string) $generator->getVersion());
        $this->assertEquals(Serialization::TEXT, $generator->getVersion()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($generator->getUrl()));
        $this->assertEquals('https://wordpress.org/', (string) $generator->getUrl());
        $this->assertEquals(Serialization::TEXT, $generator->getUrl()->getSerialization());
    }
}
