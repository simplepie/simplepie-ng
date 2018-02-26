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
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class IdTest extends AbstractTestCase
{
    // override
    public function getSimplePie(): SimplePie
    {
        return (new SimplePie())
            ->setMiddlewareStack(
                (new HandlerStack())
                    ->append(
                        (new Atom())
                            ->setCaseInsensitive()
                    )
            );
    }

    public function testId(): void
    {
        $id = $this->feed->getId();

        $this->assertEquals(Node::class, Types::getClassOrType($id));
        $this->assertEquals('tag:github.com,2008:https://github.com/skyzyx/signer/releases', (string) $id);
        $this->assertEquals('tag:github.com,2008:https://github.com/skyzyx/signer/releases', $id->getValue());
        $this->assertEquals(Serialization::TEXT, $id->getSerialization());
    }

    public function testIdAtom10(): void
    {
        $id = $this->feed->getId('atom10');

        $this->assertEquals(Node::class, Types::getClassOrType($id));
        $this->assertEquals('tag:github.com,2008:https://github.com/skyzyx/signer/releases', (string) $id);
        $this->assertEquals('tag:github.com,2008:https://github.com/skyzyx/signer/releases', $id->getValue());
        $this->assertEquals(Serialization::TEXT, $id->getSerialization());
    }
}
