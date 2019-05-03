<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Xml;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class XmlTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testHtmlCorrect(): void
    {
        $stream = $this->getFeed('/wellformed/xml/escaped_apos.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('it\'s correct', (string) $feed->getTitle());
        $this->assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testHtml(): void
    {
        $stream = $this->getFeed('/wellformed/xml/escaped_apos_html.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('it&apos;s correct', (string) $feed->getTitle());
        $this->assertEquals(Serialization::HTML, $feed->getTitle()->getSerialization());
    }

    public function testText(): void
    {
        $stream = $this->getFeed('/wellformed/xml/escaped_apos_text.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('it&apos;s correct', (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }

    public function testXhtml(): void
    {
        $stream = $this->getFeed('/wellformed/xml/escaped_apos_xhtml.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('it&amp;apos;s correct', (string) $feed->getTitle());
        $this->assertEquals(Serialization::XHTML, $feed->getTitle()->getSerialization());
    }
}
