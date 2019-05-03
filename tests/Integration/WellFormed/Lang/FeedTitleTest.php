<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Lang;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class FeedTitleTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testXmlLang(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_title_xml_lang.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('en', (string) $feed->getTitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangBlank(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_title_xml_lang_blank.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('', (string) $feed->getTitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_title_xml_lang_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('de', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('de', (string) $feed->getTitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit2(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_title_xml_lang_inherit_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('de', (string) $feed->getTitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getTitle()->getLang()->getSerialization());
    }
}
