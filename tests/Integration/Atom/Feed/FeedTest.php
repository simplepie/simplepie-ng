<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use SimplePie\Enum\DateFormat;
use SimplePie\Exception\SimplePieException;
use SimplePie\Test\Integration\AbstractTestCase;

class FeedTest extends AbstractTestCase
{
    public function testFeed(): void
    {
        $feed = $this->feed
            ->setDateFormat(DateFormat::ATOM)
            ->setOutputTimezone('America/Los_Angeles');

        $published = $feed->getPublished();

        $this->assertEquals('2016-02-26', $published->format('Y-m-d'));
        $this->assertEquals('12:30pm', $published->format('g:ia'));
        $this->assertEquals('PST', $published->format('T'));
        $this->assertEquals('-0800', $published->format('O'));
        $this->assertEquals('-08:00', $published->format('P'));
    }

    public function testFeedFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $this->feed->getDoesntExist();
    }
}
