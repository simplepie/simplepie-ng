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

class EntryRightsTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testRights(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getRights());
    }

    public function testEscapedMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights_escaped_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getRights());
        $this->assertEquals(Serialization::HTML, $entry->getRights()->getSerialization());
    }

    public function testInlineMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights_inline_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example <b>Atom</b>', (string) $entry->getRights());
        $this->assertEquals(Serialization::XHTML, $entry->getRights()->getSerialization());
    }

    public function testInlineMarkup2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights_inline_markup_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the &lt;blink&gt; tag', (string) $entry->getRights());
        $this->assertEquals(Serialization::XHTML, $entry->getRights()->getSerialization());
    }

    public function testTextPlain(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights_text_plain.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Example Atom', (string) $entry->getRights());
        $this->assertEquals(Serialization::TEXT, $entry->getRights()->getSerialization());
    }

    public function testTextPlainBrackets(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_rights_text_plain_brackets.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('History of the <blink> tag', (string) $entry->getRights());
        $this->assertEquals(Serialization::TEXT, $entry->getRights()->getSerialization());
    }
}
