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

class FeedIconLogoTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testIcon(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_icon.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $icon   = $feed->getIcon();

        $this->assertEquals('http://example.com/favicon.ico', (string) $icon);
    }

    public function testLogo(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/feed_logo.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $logo   = $feed->getLogo();

        $this->assertEquals('http://example.com/logo.jpg', (string) $logo);
    }
}
