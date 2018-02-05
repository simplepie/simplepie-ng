<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class EntryTitleTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testTitle(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getTitle());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getSerialization());
    }

    public function testBase64(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_base64.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getTitle());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getSerialization());
    }

    public function testBase642(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_base64_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('<p>History of the &lt;blink&gt; tag</p>', (string) $entry->getTitle());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getSerialization());
    }

    public function testEscapedMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_escaped_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getTitle());
        $this->assertEquals(Serialization::HTML, $entry->getTitle()->getSerialization());
    }

    public function testInlineMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_inline_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getTitle());
        $this->assertEquals(Serialization::XHTML, $entry->getTitle()->getSerialization());
    }

    public function testInlineMarkup2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_inline_markup_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the &lt;blink&gt; tag', (string) $entry->getTitle());
        $this->assertEquals(Serialization::XHTML, $entry->getTitle()->getSerialization());
    }

    public function testTextPlain(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_text_plain.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getTitle());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getSerialization());
    }

    public function testTextPlainBrackets(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_title_text_plain_brackets.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the <blink> tag', (string) $entry->getTitle());
        $this->assertEquals(Serialization::TEXT, $entry->getTitle()->getSerialization());
    }
}
