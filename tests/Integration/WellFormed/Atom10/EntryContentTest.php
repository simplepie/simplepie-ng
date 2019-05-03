<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class EntryContentTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testTypeNone(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_type.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testTypeNone2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_value.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testTypeText(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_type_text.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testText(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_text_plain.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testTextEntities(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_text_plain_brackets.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the <blink> tag', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testXhtml(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_application_xml.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getContent());
        $this->assertEquals(Serialization::XHTML, (string) $entry->getContent()->getSerialization());
    }

    public function testInlineXhtml(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_inline_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getContent());
        $this->assertEquals(Serialization::XHTML, (string) $entry->getContent()->getSerialization());
    }

    public function testInlineXhtmlEscaped(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_inline_markup_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the &lt;blink&gt; tag', (string) $entry->getContent());
        $this->assertEquals(Serialization::XHTML, (string) $entry->getContent()->getSerialization());
    }

    public function testBase64(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_base64.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testBase642(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_base64_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('<p>History of the &lt;blink&gt; tag</p>', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }

    public function testHtmlDivEscaped(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_div_escaped_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getContent());
        $this->assertEquals(Serialization::HTML, (string) $entry->getContent()->getSerialization());
    }

    public function testHtmlEscaped(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_escaped_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getContent());
        $this->assertEquals(Serialization::HTML, (string) $entry->getContent()->getSerialization());
    }

    public function testSrc(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_content_src.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('http://example.com/movie.mp4', (string) $entry->getContent());
        $this->assertEquals(Serialization::TEXT, (string) $entry->getContent()->getSerialization());
    }
}
