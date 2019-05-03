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

class EntryIdTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testId(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://example.com/', (string) $entry->getId());
    }

    public function testGuid(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_map_guid.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://example.com/', (string) $entry->getGuid());
    }

    public function testNoNormalization1(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_1.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.example.org/thing', (string) $entry->getId());
    }

    public function testNoNormalization2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.example.org/Thing', (string) $entry->getId());
    }

    public function testNoNormalization3(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_3.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.EXAMPLE.org/thing', (string) $entry->getId());
    }

    public function testNoNormalization4(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_4.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('HTTP://www.example.org/thing', (string) $entry->getId());
    }

    public function testNoNormalization5(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_5.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.example.com/~bob', (string) $entry->getId());
    }

    public function testNoNormalization6(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_6.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.example.com/%7ebob', (string) $entry->getId());
    }

    public function testNoNormalization7(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_no_normalization_7.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('http://www.example.com/%7Ebob', (string) $entry->getId());
    }

    public function testWithAttr(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_id_with_attributes.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        static::assertEquals('right', (string) $entry->getId());
    }
}
