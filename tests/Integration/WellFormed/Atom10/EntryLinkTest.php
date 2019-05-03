<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Test\Integration\AbstractTestCase;

class EntryLinkTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testHref(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_alternate_map_link.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://www.example.com/', (string) $link->getHref());
        static::assertEquals('http://www.example.com/', (string) $link->getUri());
        static::assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_alternate_map_link_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://www.example.com/', (string) $link->getHref());
        static::assertEquals('http://www.example.com/', (string) $link->getUri());
        static::assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref3(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_alternate_map_link_3.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks($feed->getDefaultNs(), 'alternate')[0];

        static::assertEquals('http://www.example.com/alternate', (string) $link->getHref());
        static::assertEquals('http://www.example.com/alternate', (string) $link->getUri());
        static::assertEquals('http://www.example.com/alternate', (string) $link->getUrl());
    }

    public function testHref4(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_href.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://www.example.com/', (string) $link->getHref());
    }

    public function testHreflang(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_hreflang.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('en', (string) $link->getHreflang());
        static::assertEquals('en', (string) $link->getLang());
        static::assertEquals('en', (string) $link->getLanguage());
    }

    public function testLength(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_length.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('42301', (string) $link->getLength());
    }

    public function testMultiple(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_multiple.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks($feed->getDefaultNs(), 'service.post')[0];

        static::assertEquals('service.post', (string) $link->getRel());
        static::assertEquals('service.post', (string) $link->getRelationship());
        static::assertEquals('application/atom+xml', (string) $link->getMediaType());
        static::assertEquals('application/atom+xml', (string) $link->getType());
        static::assertEquals('http://www.example.com/post', (string) $link->getHref());
        static::assertEquals('http://www.example.com/post', (string) $link->getUri());
        static::assertEquals('http://www.example.com/post', (string) $link->getUrl());
    }

    public function testNoRel(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_no_rel.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('alternate', (string) $link->getRel());
        static::assertEquals('alternate', (string) $link->getRelationship());
    }

    public function testRel(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('alternate', (string) $link->getRel());
        static::assertEquals('alternate', (string) $link->getRelationship());
    }

    public function testRelEnclosure(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_enclosure.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('enclosure', (string) $link->getRel());
        static::assertEquals('enclosure', (string) $link->getRelationship());
    }

    public function testRelEnclosureLength(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_enclosure_map_enclosure_length.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('42301', (string) $link->getLength());
    }

    public function testRelEnclosureType(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_enclosure_map_enclosure_type.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('video/mpeg4', (string) $link->getMediaType());
        static::assertEquals('video/mpeg4', (string) $link->getType());
    }

    public function testRelEnclosureUrl(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_enclosure_map_enclosure_url.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://www.example.com/movie.mp4', (string) $link->getHref());
        static::assertEquals('http://www.example.com/movie.mp4', (string) $link->getUri());
        static::assertEquals('http://www.example.com/movie.mp4', (string) $link->getUrl());
    }

    public function testRelLicense(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_license.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://www.creativecommons.org/licenses/by-nc/1.0', (string) $link->getHref());
        static::assertEquals('http://www.creativecommons.org/licenses/by-nc/1.0', (string) $link->getUri());
        static::assertEquals('http://www.creativecommons.org/licenses/by-nc/1.0', (string) $link->getUrl());
    }

    public function testRelOther(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_other.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('http://feedparser.org/rel/test', (string) $link->getRel());
        static::assertEquals('http://feedparser.org/rel/test', (string) $link->getRelationship());
    }

    public function testRelRelated(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_related.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('related', (string) $link->getRel());
        static::assertEquals('related', (string) $link->getRelationship());
    }

    public function testRelSelf(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_self.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('self', (string) $link->getRel());
        static::assertEquals('self', (string) $link->getRelationship());
    }

    public function testRelVia(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_rel_via.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('via', (string) $link->getRel());
        static::assertEquals('via', (string) $link->getRelationship());
    }

    public function testTitle(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_title.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('Example title', (string) $link->getTitle());
    }

    public function testType(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_type.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        static::assertEquals('text/html', (string) $link->getMediaType());
        static::assertEquals('text/html', (string) $link->getType());
    }
}
