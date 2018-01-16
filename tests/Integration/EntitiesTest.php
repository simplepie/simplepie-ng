<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class EntitiesTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    /**
     * Data Provider
     *
     * @return iterable
     */
    public function feeds(): iterable
    {
        $pattern = sprintf('%s/entities/*.xml', $this->getFeedDir());
        $files = glob($pattern, GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            yield [str_replace($this->getFeedDir(), '', $file)];
        }
    }

    /**
     * @dataProvider feeds
     */
    public function testEntities($feed): void
    {
        $stream = $this->getFeed($feed);
        $parser = $this->simplepie->parseXml($stream, true);
        $xq     = $parser->xpath()->query('/comment()')[0];
        $feed   = $parser->getFeed();

        preg_match("/Expect:\s+feed\['title'\] == '([^']*)'/", (string) $xq->textContent, $m);
        $title = json_decode(sprintf('["%s"]', $m[1]))[0];

        $this->assertEquals($title, (string) $feed->getTitle());
        $this->assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }
}
