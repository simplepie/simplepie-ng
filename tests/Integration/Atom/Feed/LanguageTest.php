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

class LanguageTest extends AbstractTestCase
{
    public function testLang(): void
    {
        $lang = $this->feed->getLang();

        static::assertEquals(Node::class, Types::getClassOrType($lang));
        static::assertEquals('en_US', (string) $lang);
        static::assertEquals('en_US', $lang->getValue());
        static::assertEquals(Serialization::TEXT, $lang->getSerialization());
    }

    public function testLangAtom10(): void
    {
        $lang = $this->feed->getLang('atom10');

        static::assertEquals(Node::class, Types::getClassOrType($lang));
        static::assertEquals('en_US', (string) $lang);
        static::assertEquals('en_US', $lang->getValue());
        static::assertEquals(Serialization::TEXT, $lang->getSerialization());
    }

    public function testLanguage(): void
    {
        $lang = $this->feed->getLanguage();

        static::assertEquals(Node::class, Types::getClassOrType($lang));
        static::assertEquals('en_US', (string) $lang);
        static::assertEquals('en_US', $lang->getValue());
        static::assertEquals(Serialization::TEXT, $lang->getSerialization());
    }

    public function testLanguageAtom10(): void
    {
        $lang = $this->feed->getLanguage('atom10');

        static::assertEquals(Node::class, Types::getClassOrType($lang));
        static::assertEquals('en_US', (string) $lang);
        static::assertEquals('en_US', $lang->getValue());
        static::assertEquals(Serialization::TEXT, $lang->getSerialization());
    }
}
