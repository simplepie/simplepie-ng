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

class EntryContentTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testXmlLangInherit(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        static::assertEquals('en', (string) $entry->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        static::assertEquals('en', (string) $entry->getContent()->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getContent()->getLang()->getSerialization());
    }

    public function testXmlLangInherit2(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_inherit_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('fr', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        static::assertEquals('fr', (string) $entry->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        static::assertEquals('en', (string) $entry->getContent()->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getContent()->getLang()->getSerialization());
    }

    public function testXmlLangInherit3(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_inherit_3.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('fr', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        static::assertEquals('fr', (string) $entry->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        static::assertEquals('en', (string) $entry->getContent()->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getContent()->getLang()->getSerialization());
    }

    public function testXmlLangInherit4(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_inherit_4.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('en', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        static::assertEquals('en', (string) $entry->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        static::assertEquals('en', (string) $entry->getContent()->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getContent()->getLang()->getSerialization());
    }

    public function testXmlLangUnderscore(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_underscore.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('', (string) $feed->getLang());
        static::assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        static::assertEquals('', (string) $entry->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        static::assertEquals('en_US', (string) $entry->getContent()->getLang());
        static::assertEquals(Serialization::TEXT, $entry->getContent()->getLang()->getSerialization());
    }
}
