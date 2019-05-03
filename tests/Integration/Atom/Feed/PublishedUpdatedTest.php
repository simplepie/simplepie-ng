<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DateTime;
use DateTimeZone;
use SimplePie\Enum\DateFormat;
use SimplePie\Test\Integration\AbstractTestCase;
use Skyzyx\UtilityPack\Types;

class PublishedUpdatedTest extends AbstractTestCase
{
    public function testPublished(): void
    {
        $published = $this->feed->getPublished();

        $this->assertEquals(DateTime::class, Types::getClassOrType($published));
        $this->assertEquals(1456518612, $published->getTimestamp());
        $this->assertEquals(0, $published->getOffset());

        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::RFC3339));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ISO8601));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ATOM));
        $this->assertEquals('Fri, 26 Feb 16 20:30:12 +0000', $published->format(DateFormat::RFC822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RFC2822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RSS20));

        $this->assertEquals(DateTimeZone::class, Types::getClassOrType($published->getTimezone()));
        $this->assertEquals('UTC', $published->getTimezone()->getName());
    }

    public function testPublishedAtom10(): void
    {
        $published = $this->feed->getPublished('atom10');

        $this->assertEquals(DateTime::class, Types::getClassOrType($published));
        $this->assertEquals(1456518612, $published->getTimestamp());
        $this->assertEquals(0, $published->getOffset());

        $this->assertEquals(DateTimeZone::class, Types::getClassOrType($published->getTimezone()));
        $this->assertEquals('UTC', $published->getTimezone()->getName());
    }

    public function testPublishedUsWestCoast(): void
    {
        $published = $this->feed->getPublished()->setTimezone(new \DateTimeZone('America/Los_Angeles'));

        $this->assertEquals(1456518612, $published->getTimestamp());
        $this->assertEquals(-28800, $published->getOffset());

        $this->assertEquals(DateTimeZone::class, Types::getClassOrType($published->getTimezone()));
        $this->assertEquals('America/Los_Angeles', $published->getTimezone()->getName());
    }

    public function testPubDate(): void
    {
        $published = $this->feed->getPubDate();

        $this->assertEquals(DateTime::class, Types::getClassOrType($published));
        $this->assertEquals(1456518612, $published->getTimestamp());
        $this->assertEquals(0, $published->getOffset());

        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::RFC3339));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ISO8601));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ATOM));
        $this->assertEquals('Fri, 26 Feb 16 20:30:12 +0000', $published->format(DateFormat::RFC822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RFC2822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RSS20));

        $this->assertEquals(DateTimeZone::class, Types::getClassOrType($published->getTimezone()));
        $this->assertEquals('UTC', $published->getTimezone()->getName());
    }

    public function testUpdated(): void
    {
        $published = $this->feed->getUpdated();

        $this->assertEquals(DateTime::class, Types::getClassOrType($published));
        $this->assertEquals(1456518612, $published->getTimestamp());
        $this->assertEquals(0, $published->getOffset());

        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::RFC3339));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ISO8601));
        $this->assertEquals('2016-02-26T20:30:12+00:00', $published->format(DateFormat::ATOM));
        $this->assertEquals('Fri, 26 Feb 16 20:30:12 +0000', $published->format(DateFormat::RFC822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RFC2822));
        $this->assertEquals('Fri, 26 Feb 2016 20:30:12 +0000', $published->format(DateFormat::RSS20));

        $this->assertEquals(DateTimeZone::class, Types::getClassOrType($published->getTimezone()));
        $this->assertEquals('UTC', $published->getTimezone()->getName());
    }
}
