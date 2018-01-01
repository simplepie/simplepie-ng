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

class FeedSubtitleTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testSubtitle(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example Atom', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::TEXT, $feed->getSubtitle()->getSerialization());
    }

    public function testBase64(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_base64.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::TEXT, $feed->getSubtitle()->getSerialization());
    }

    public function testBase642(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_base64_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('<p>History of the &lt;blink&gt; tag</p>', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::TEXT, $feed->getSubtitle()->getSerialization());
    }

    public function testEscapedMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_escaped_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::HTML, $feed->getSubtitle()->getSerialization());
    }

    public function testInlineMarkup(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_inline_markup.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example <b>Atom</b>', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::XHTML, $feed->getSubtitle()->getSerialization());
    }

    public function testInlineMarkup2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_inline_markup_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('History of the &lt;blink&gt; tag', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::XHTML, $feed->getSubtitle()->getSerialization());
    }

    public function testTextPlain(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_subtitle_text_plain.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('Example Atom', (string) $feed->getSubtitle());
        $this->assertEquals(Serialization::TEXT, $feed->getSubtitle()->getSerialization());
    }
}
