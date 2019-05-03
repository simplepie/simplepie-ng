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

class FeedIdTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testId(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_id.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        static::assertEquals('http://example.com/', (string) $feed->getId());
        static::assertEquals('http://example.com/', (string) $feed->getGuid());
    }
}
