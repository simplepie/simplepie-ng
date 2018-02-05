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

class FeedMiscTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testMissingQuoteInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/missing_quote_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('<a href=http://example.com/">example</a>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testQna(): void
    {
        // FeedParser.py was wrong on this test.
        $stream = $this->getFeed('/wellformed/atom10/qna.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $this->assertEquals('Q&A session', (string) $entry->getTitle());
        $this->assertEquals(Serialization::HTML, $entry->getTitle()->getSerialization());
    }

    public function testQuoteInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/quote_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('<a title="&quot;test&quot;">test</a>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testTagInAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/tag_in_attr.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals(
            '<img src="http://example.com/cat-dog.jpg" alt="cat<br />dog">',
            (string) $feed->getTitle()
        );
        $this->assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }
}
