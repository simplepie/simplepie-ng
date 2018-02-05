<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Unit\Parser;

use DateTime;
use SimplePie\Enum\DateFormat;
use SimplePie\Parser\Date;
use SimplePie\Test\Unit\AbstractTestCase;
use Skyzyx\UtilityPack\Types;

class DateTest extends AbstractTestCase
{
    public function providerUtc()
    {
        return [
            'sql'       => [new Date('2017-12-17 23:29:21')],
            'rfc822'    => [new Date('17 Dec 2017 23:29:21')],
            'rfc2822'   => [new Date('Sun, 17 Dec 2017 23:29:21')],
            'iso8601'   => [new Date('2017-12-17T23:29:21')],
            'rfc3339-1' => [new Date('2017-12-17T23:29:21+0000')],
            'rfc3339-2' => [new Date('2017-12-17T23:29:21+00:00')],
            'rfc3339-3' => [new Date('2017-12-17T23:29:21Z')],

            // Leveraging `createFromFormat`
            'epoch'     => [new Date('1513553361', 'UTC', 'U')],
            'english-1' => [
                new Date(
                    'The 17th day of December, in the year 2017, at 3:29 PM and 21 seconds, in the '
                        . 'America/Los_Angeles timezone.',
                    'UTC',
                    '\T\h\e jS \d\a\y \o\f F, \i\n \t\h\e \y\e\a\r Y, \a\t g:i A \a\n\d s \s\e\c\o\n\d\s, \i\n \t\h\e '
                        . 'e \t\i\m\e\z\o\n\e.'
                ),
            ],
        ];
    }

    /**
     * @dataProvider providerUtc
     *
     * @param mixed $date
     */
    public function testUtc($date): void
    {
        $this->assertEquals('string', Types::getClassOrType($date->getDatestamp()));
        $this->assertEquals('UTC', $date->getOutputTimezone());

        /** @var \DateTime */
        $dateTime = $date->getDateTime();

        $this->assertEquals('1513553361', $dateTime->format('U'));
        $this->assertEquals('2017-12-17T23:29:21+00:00', $dateTime->format(DateFormat::ATOM));
        $this->assertEquals('2017-12-17T23:29:21+00:00', $dateTime->format(DateFormat::ISO8601));
        $this->assertEquals('2017-12-17T23:29:21+00:00', $dateTime->format(DateFormat::RFC3339));
        $this->assertEquals('Sun, 17 Dec 2017 23:29:21 +0000', $dateTime->format(DateFormat::RSS20));
        $this->assertEquals('Sun, 17 Dec 2017 23:29:21 +0000', $dateTime->format(DateFormat::RFC2822));
        $this->assertEquals('Sun, 17 Dec 17 23:29:21 +0000', $dateTime->format(DateFormat::RFC822));

        $interval = $dateTime->diff(new DateTime('2018-01-01'));
        $this->assertEquals('14 days', $interval->format('%a days'));
    }
}
