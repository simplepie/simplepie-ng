<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Lang;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class EntryTitleTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testXmlLang(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('en', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangBlank(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang_blank.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('en', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('en', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('en', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('en', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('en', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit2(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang_inherit_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('fr', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('fr', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('en', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit3(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang_inherit_3.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('fr', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('fr', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('en', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }

    public function testXmlLangInherit4(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_title_xml_lang_inherit_4.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('en', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());

        $entry = $feed->getEntries()[0];

        $this->assertEquals('en', (string) $entry->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getLang()->getSerialization());

        $this->assertEquals('en', (string) $entry->getTitle()->getLang());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getLang()->getSerialization());
    }
}
