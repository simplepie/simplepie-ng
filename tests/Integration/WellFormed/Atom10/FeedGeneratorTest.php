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

class FeedGeneratorTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testGenerator(): void
    {
        $stream    = $this->getFeed('/wellformed/atom10/feed_generator.xml');
        $parser    = $this->simplepie->parseXml($stream);
        $feed      = $parser->getFeed();
        $generator = $feed->getGenerator();

        $this->assertEquals('Example generator 2.65', (string) $generator);
    }

    public function testName(): void
    {
        $stream    = $this->getFeed('/wellformed/atom10/feed_generator_name.xml');
        $parser    = $this->simplepie->parseXml($stream);
        $feed      = $parser->getFeed();
        $generator = $feed->getGenerator();

        $this->assertEquals('Example generator', (string) $generator->getName());
    }

    public function testUrl(): void
    {
        $stream    = $this->getFeed('/wellformed/atom10/feed_generator_url.xml');
        $parser    = $this->simplepie->parseXml($stream);
        $feed      = $parser->getFeed();
        $generator = $feed->getGenerator();

        $this->assertEquals('http://example.com/', (string) $generator->getUrl());
        $this->assertEquals('http://example.com/', (string) $generator->getUri());
    }

    public function testVersion(): void
    {
        $stream    = $this->getFeed('/wellformed/atom10/feed_generator_version.xml');
        $parser    = $this->simplepie->parseXml($stream);
        $feed      = $parser->getFeed();
        $generator = $feed->getGenerator();

        $this->assertEquals('2.65', (string) $generator->getVersion());
    }
}
