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

class FeedTitleTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testTitle(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example Atom', (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }

    public function testBase64(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_base64.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }

    public function testBase642(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_base64_2.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('<p>History of the &lt;blink&gt; tag</p>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }

    public function testEscapedMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_escaped_markup.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testInlineMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_inline_markup.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getTitle());
        $this->assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testInlineMarkup2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_inline_markup_2.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('History of the &lt;blink&gt; tag', (string) $feed->getTitle());
        $this->assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }

    public function testTextPlain(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_title_text_plain.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example Atom', (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }
}
