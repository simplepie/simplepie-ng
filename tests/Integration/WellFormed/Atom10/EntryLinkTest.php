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

class EntryLinkTest extends AbstractTestCase
{
    public function setUp(): void
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

        $this->assertEquals('http://www.example.com/', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_alternate_map_link_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = $entry->getLinks()[0];

        $this->assertEquals('http://www.example.com/', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/', (string) $link->getUrl());
    }

    public function testHref3(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_link_alternate_map_link_3.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $link   = \array_filter($entry->getLinks(), static function ($link) {
            return 'alternate' === (string) $link->getRelationship();
        })[0];

        $this->assertEquals('http://www.example.com/alternate', (string) $link->getHref());
        $this->assertEquals('http://www.example.com/alternate', (string) $link->getUri());
        $this->assertEquals('http://www.example.com/alternate', (string) $link->getUrl());
    }
}
