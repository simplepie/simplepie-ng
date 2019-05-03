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

        static::assertEquals(Link::class, Types::getClassOrType($link));
        static::assertEquals('https://github.com/skyzyx/signer/releases.atom', (string) $link);

        static::assertEquals(DOMElement::class, Types::getClassOrType($link->getNode()));

        static::assertEquals(Node::class, Types::getClassOrType($link->getRelationship()));
        static::assertEquals('self', (string) $link->getRelationship());
        static::assertEquals(Serialization::TEXT, $link->getRelationship()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($link->getUrl()));
        static::assertEquals('https://github.com/skyzyx/signer/releases.atom', (string) $link->getUrl());
        static::assertEquals(Serialization::TEXT, $link->getUrl()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($link->getMediaType()));
        static::assertEquals('application/atom+xml', (string) $link->getMediaType());
        static::assertEquals(Serialization::TEXT, $link->getMediaType()->getSerialization());
    }

    public function testLinks(): void
    {
        $links = $this->feed->getLinks();

        foreach ($links as $link) {
            static::assertEquals(Link::class, Types::getClassOrType($link));
            static::assertEquals(DOMElement::class, Types::getClassOrType($link->getNode()));
            static::assertEquals(Node::class, Types::getClassOrType($link->getRelationship()));
            static::assertEquals(Serialization::TEXT, $link->getRelationship()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($link->getUrl()));
            static::assertEquals(Serialization::TEXT, $link->getUrl()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($link->getMediaType()));
            static::assertEquals(Serialization::TEXT, $link->getMediaType()->getSerialization());
        }
    }

    public function testLinkAliases(): void
    {
        $links = $this->feed->getLinks();
        $link  = $links[0];

        static::assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getUrl());
        static::assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getUri());
        static::assertEquals('https://github.com/skyzyx/signer/releases', (string) $link->getHref());

        static::assertEquals('alternate', (string) $link->getRel());
        static::assertEquals('alternate', (string) $link->getRelationship());

        static::assertEquals('', (string) $link->getLang());
        static::assertEquals('', (string) $link->getLanguage());
        static::assertEquals('', (string) $link->getHreflang());
    }

    public function testLinkFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $links = $this->feed->getLinks();
        $links[0]->getDoesntExist();
    }
}
