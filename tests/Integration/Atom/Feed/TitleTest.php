<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class TitleTest extends AbstractTestCase
{
    public function testTitle(): void
    {
        $title = $this->feed->getTitle();

        static::assertEquals(Node::class, Types::getClassOrType($title));
        static::assertEquals('Release notes from signer', (string) $title);
        static::assertEquals('Release notes from signer', $title->getValue());
        static::assertEquals(Serialization::TEXT, $title->getSerialization());
    }

    public function testTitleAtom10(): void
    {
        $title = $this->feed->getTitle('atom10');

        static::assertEquals(Node::class, Types::getClassOrType($title));
        static::assertEquals('Release notes from signer', (string) $title);
        static::assertEquals('Release notes from signer', $title->getValue());
        static::assertEquals(Serialization::TEXT, $title->getSerialization());
    }
}
