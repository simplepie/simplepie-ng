<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use SimplePie\Type\Person;
use Skyzyx\UtilityPack\Types;

/**
 * @coversNothing
 */
class PersonTest extends AbstractTestCase
{
    public function testPerson(): void
    {
        $person = $this->feed->getAuthor();

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
    }
}
