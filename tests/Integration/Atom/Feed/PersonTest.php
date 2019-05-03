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

        $this->assertEquals(Person::class, Types::getClassOrType($person));
        $this->assertEquals('Ryan Parman <http://ryanparman.com>', (string) $person);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($person->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($person->getName()));
        $this->assertEquals('Ryan Parman', (string) $person->getName());
        $this->assertEquals(Serialization::TEXT, $person->getName()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($person->getUrl()));
        $this->assertEquals('http://ryanparman.com', (string) $person->getUrl());
        $this->assertEquals(Serialization::TEXT, $person->getUrl()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($person->getEmail()));
        $this->assertEquals('ryan@ryanparman.com', (string) $person->getEmail());
        $this->assertEquals(Serialization::TEXT, $person->getEmail()->getSerialization());
    }

    public function testPeople(): void
    {
        $people = $this->feed->getContributors();

        foreach ($people as $person) {
            $this->assertEquals(Person::class, Types::getClassOrType($person));
            $this->assertEquals(DOMElement::class, Types::getClassOrType($person->getNode()));
            $this->assertEquals(Node::class, Types::getClassOrType($person->getName()));
            $this->assertEquals(Serialization::TEXT, $person->getName()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($person->getUrl()));
            $this->assertEquals(Serialization::TEXT, $person->getUrl()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($person->getEmail()));
            $this->assertEquals(Serialization::TEXT, $person->getEmail()->getSerialization());
        }

        $this->assertEquals('Eric Baird', (string) $people[0]);
        $this->assertEquals('Jeff Ringer <jeff@ufks.com>', (string) $people[1]);
        $this->assertEquals('http://ryanparman.com', (string) $people[2]);
        $this->assertEquals('ryan@ryanparman.com', (string) $people[3]);
    }

    public function testPersonAliases(): void
    {
        $person = $this->feed->getAuthors()[0];

        $this->assertEquals('http://ryanparman.com', (string) $person->getUrl());
        $this->assertEquals('http://ryanparman.com', (string) $person->getUri());
    }

    public function testPersonFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $person = $this->feed->getAuthors()[0];
        $person->getDoesntExist();
    }
}
