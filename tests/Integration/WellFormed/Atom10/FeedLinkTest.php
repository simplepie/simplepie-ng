<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Test\Integration\AbstractTestCase;

class FeedLinkTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testHref(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_alternate_map_link.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('http://www.example.com/', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_alternate_map_link_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('http://www.example.com/', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref3(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_href.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('http://www.example.com/', (string) $link->getHref());
    }

    public function testHreflang(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_hreflang.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('en', (string) $link->getHreflang());
        $this->assertEquals('en', (string) $link->getLang());
        $this->assertEquals('en', (string) $link->getLanguage());
    }

    public function testLength(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_length.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('42301', (string) $link->getLength());
    }

    public function testMultiple(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_multiple.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks($feed->getDefaultNs(), 'service.post')[0];

        $this->assertEquals('service.post', (string) $link->getRel());
        $this->assertEquals('service.post', (string) $link->getRelationship());
        $this->assertEquals('application/atom+xml', (string) $link->getMediaType());
        $this->assertEquals('application/atom+xml', (string) $link->getType());
        $this->assertEquals('http://www.example.com/post', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/post', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/post', (string) $link->getUrl());
    }

    public function testNoRel(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_no_rel.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('alternate', (string) $link->getRel());
        $this->assertEquals('alternate', (string) $link->getRelationship());
    }

    public function testRel(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('alternate', (string) $link->getRel());
        $this->assertEquals('alternate', (string) $link->getRelationship());
    }

    public function testRelOther(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel_other.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('http://feedparser.org/rel/test', (string) $link->getRel());
        $this->assertEquals('http://feedparser.org/rel/test', (string) $link->getRelationship());
    }

    public function testRelRelated(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel_related.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('related', (string) $link->getRel());
        $this->assertEquals('related', (string) $link->getRelationship());
    }

    public function testRelSelf(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel_self.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('self', (string) $link->getRel());
        $this->assertEquals('self', (string) $link->getRelationship());
    }

    public function testRelSelfDefaultType(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel_self_default_type.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('application/atom+xml', (string) $link->getMediaType());
        $this->assertEquals('application/atom+xml', (string) $link->getType());
    }

    public function testRelVia(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_rel_via.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('via', (string) $link->getRel());
        $this->assertEquals('via', (string) $link->getRelationship());
    }

    public function testTitle(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_title.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('Example title', (string) $link->getTitle());
    }

    public function testType(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_link_type.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $link   = $feed->getLinks()[0];

        $this->assertEquals('text/html', (string) $link->getMediaType());
        $this->assertEquals('text/html', (string) $link->getType());
    }
}
