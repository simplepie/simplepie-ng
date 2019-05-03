<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Amp;

use SimplePie\Test\Integration\AbstractTestCase;

class AmpTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    /**
     * Data Provider.
     */
    public function feeds(): iterable
    {
        $pattern = \sprintf('%s/wellformed/amp/*.xml', $this->getFeedDir());
        $files   = \glob($pattern, \GLOB_MARK);

        foreach ($files as $file) {
            yield [\str_replace($this->getFeedDir(), '', $file)];
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
        $entry  = $feed->getEntries()[0];

        \preg_match(
            "/Expect:\\s+not bozo and entries\\[0\\]\\['title_detail'\\]\\['value'\\] == '([^']*)'/",
            (string) $xq->textContent,
            $m
        );
        $title = $m[1];

        static::assertEquals($title, $entry->getTitle()->getValue());
    }
}
