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

class FeedSubtitleTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testXmlLang(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_subtitle_xml_lang.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('en', (string) $feed->getSubtitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getSubtitle()->getLang()->getSerialization());
    }

    public function testXmlLangBlank(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_subtitle_xml_lang_blank.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('', (string) $feed->getSubtitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getSubtitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_subtitle_xml_lang_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('en', (string) $feed->getSubtitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getSubtitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit2(): void
    {
        $stream = $this->getFeed('/wellformed/lang/feed_subtitle_xml_lang_inherit_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        static::assertEquals('fr', (string) $feed->getSubtitle()->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getSubtitle()->getLang()->getSerialization());
    }
}
