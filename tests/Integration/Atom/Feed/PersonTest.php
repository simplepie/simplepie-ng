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
use SimplePie\Type\Node;
use SimplePie\Type\Person;
use Skyzyx\UtilityPack\Types;

class PersonTest extends AbstractTestCase
{
    public function testPerson(): void
    {
        $person = $this->feed->getAuthors()[0];

        static::assertEquals(Person::class, Types::getClassOrType($person));
        static::assertEquals('Ryan Parman <http://ryanparman.com>', (string) $person);

        static::assertEquals(DOMElement::class, Types::getClassOrType($person->getNode()));

        static::assertEquals(Node::class, Types::getClassOrType($person->getName()));
        static::assertEquals('Ryan Parman', (string) $person->getName());
        static::assertEquals(Serialization::TEXT, $person->getName()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($person->getUrl()));
        static::assertEquals('http://ryanparman.com', (string) $person->getUrl());
        static::assertEquals(Serialization::TEXT, $person->getUrl()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($person->getEmail()));
        static::assertEquals('ryan@ryanparman.com', (string) $person->getEmail());
        static::assertEquals(Serialization::TEXT, $person->getEmail()->getSerialization());
    }

    public function testPeople(): void
    {
        $people = $this->feed->getContributors();

        foreach ($people as $person) {
            static::assertEquals(Person::class, Types::getClassOrType($person));
            static::assertEquals(DOMElement::class, Types::getClassOrType($person->getNode()));
            static::assertEquals(Node::class, Types::getClassOrType($person->getName()));
            static::assertEquals(Serialization::TEXT, $person->getName()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($person->getUrl()));
            static::assertEquals(Serialization::TEXT, $person->getUrl()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($person->getEmail()));
            static::assertEquals(Serialization::TEXT, $person->getEmail()->getSerialization());
        }

        static::assertEquals('Eric Baird', (string) $people[0]);
        static::assertEquals('Jeff Ringer <jeff@ufks.com>', (string) $people[1]);
        static::assertEquals('http://ryanparman.com', (string) $people[2]);
        static::assertEquals('ryan@ryanparman.com', (string) $people[3]);
    }

    public function testPersonAliases(): void
    {
        $person = $this->feed->getAuthors()[0];

        static::assertEquals('http://ryanparman.com', (string) $person->getUrl());
        static::assertEquals('http://ryanparman.com', (string) $person->getUri());
    }

    public function testPersonFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $person = $this->feed->getAuthors()[0];
        $person->getDoesntExist();
    }
}
