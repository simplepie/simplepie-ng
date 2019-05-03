<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Exception\SimplePieException;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Generator;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class GeneratorTest extends AbstractTestCase
{
    public function testGenerator(): void
    {
        $generator = $this->feed->getGenerator();

        static::assertEquals(Generator::class, Types::getClassOrType($generator));
        static::assertEquals('WordPress 4.7.5', (string) $generator);

        static::assertEquals(DOMElement::class, Types::getClassOrType($generator->getNode()));

        static::assertEquals(Node::class, Types::getClassOrType($generator->getName()));
        static::assertEquals('WordPress', (string) $generator->getName());
        static::assertEquals(Serialization::TEXT, $generator->getName()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($generator->getVersion()));
        static::assertEquals('4.7.5', (string) $generator->getVersion());
        static::assertEquals(Serialization::TEXT, $generator->getVersion()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($generator->getUrl()));
        static::assertEquals('https://wordpress.org/', (string) $generator->getUrl());
        static::assertEquals(Serialization::TEXT, $generator->getUrl()->getSerialization());
    }

    public function testGeneratorAliases(): void
    {
        $generator = $this->feed->getGenerator();

        static::assertEquals('https://wordpress.org/', (string) $generator->getUrl());
        static::assertEquals('https://wordpress.org/', (string) $generator->getUri());
    }

    public function testGeneratorFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $generator = $this->feed->getGenerator();
        $generator->getDoesntExist();
    }
}
