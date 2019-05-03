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

class FeedMiscTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testAmpersandInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/ampersand_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('Example <a href="http://example.com/?a=1&amp;b=2">test</a>', (string) $feed->getTitle());
        static::assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testLinkNoRel(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/atom_with_link_tag_for_url_unmarked.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('http://www.innoq.com/planet/', (string) $feed->getLinks()[1]->getUri());
        static::assertEquals('alternate', (string) $feed->getLinks()[1]->getRel());
    }

    public function testMissingQuoteInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/missing_quote_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('<a href=http://example.com/">example</a>', (string) $feed->getTitle());
        static::assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testQna(): void
    {
        // FeedParser.py was wrong on this test.
        $stream = $this->getFeed('/wellformed/atom10/qna.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('Q&A session', (string) $entry->getTitle());
        static::assertEquals(Serialization::HTML, $entry->getTitle()->getSerialization());
    }

    public function testQuoteInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/quote_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('<a title="&quot;test&quot;">test</a>', (string) $feed->getTitle());
        static::assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testTagInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/tag_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals(
            '<img src="http://example.com/cat-dog.jpg" alt="cat<br />dog">',
            (string) $feed->getTitle()
        );
        static::assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testRelativeUri(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/relative_uri.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('http://example.com/test/', (string) $feed->getTitle()->getBase());
        static::assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testRelativeUriInherit(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/relative_uri_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('http://example.com/test/', (string) $feed->getTitle()->getBase());
        static::assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testRelativeUriInherit2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/relative_uri_inherit_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('http://example.com/test/', (string) $feed->getTitle()->getBase());
        static::assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }
}
