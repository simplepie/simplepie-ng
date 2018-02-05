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
use SimplePie\Exception\SimplePieException;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Link;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class LinkTest extends AbstractTestCase
{
    public function testLink(): void
    {
        $link = \array_values(
            \array_filter($this->feed->getLinks(), static function ($l): bool {
                return 'self' === $l->getRelationship()->getValue();
            })
        )[0];

        $this->assertEquals(Link::class, Types::getClassOrType($link));
        $this->assertEquals('https://github.com/skyzyx/signer/releases.atom', (string) $link);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($link->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($link->getRelationship()));
        $this->assertEquals('self', (string) $link->getRelationship());
        $this->assertEquals(Serialization::TEXT, $link->getRelationship()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($link->getUrl()));
        $this->assertEquals('https://github.com/skyzyx/signer/releases.atom', (string) $link->getUrl());
        $this->assertEquals(Serialization::TEXT, $link->getUrl()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($link->getMediaType()));
        $this->assertEquals('application/atom+xml', (string) $link->getMediaType());
        $this->assertEquals(Serialization::TEXT, $link->getMediaType()->getSerialization());
    }

    public function testLinks(): void
    {
        $links = $this->feed->getLinks();

        foreach ($links as $link) {
            $this->assertEquals(Link::class, Types::getClassOrType($link));
            $this->assertEquals(DOMElement::class, Types::getClassOrType($link->getNode()));
            $this->assertEquals(Node::class, Types::getClassOrType($link->getRelationship()));
            $this->assertEquals(Serialization::TEXT, $link->getRelationship()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($link->getUrl()));
            $this->assertEquals(Serialization::TEXT, $link->getUrl()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($link->getMediaType()));
            $this->assertEquals(Serialization::TEXT, $link->getMediaType()->getSerialization());
        }
    }

    public function testLinkAliases(): void
    {
        $links = $this->feed->getLinks();
        $link  = $links[0];

        $this->assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getUrl());
        $this->assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getUri());
        $this->assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getHref());

        $this->assertEquals('alternate', (string) $link->getRel());
        $this->assertEquals('alternate', (string) $link->getRelationship());

        $this->assertEquals('', (string) $link->getLang());
        $this->assertEquals('', (string) $link->getLanguage());
        $this->assertEquals('', (string) $link->getHreflang());
    }

    public function testLinkFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $links = $this->feed->getLinks();
        $links[0]->getDoesntExist();
    }
}
