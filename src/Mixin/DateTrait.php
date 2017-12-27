<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

/**
 * Shared code for working with elements which manage dates.
 */
trait DateTrait
{
    /**
     * The format that should be used when determining how to parse a date from a date string.
     *
     * @var string
     */
    protected $createFromFormat;

    /**
     * The preferred timezone to use for date output.
     *
     * @var string
     */
    protected $outputTimezone;

    /**
     * Allows the user to help the date parser by providing the format of the datestamp in the feed.
     *
     * This will be passed into `DateTime::createFromFormat()` at parse-time.
     *
     * @param string $createFromFormat The format of the datestamp in the feed.
     *
     * @return self
     *
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    public function setDateFormat(string $createFromFormat): self
    {
        $this->createFromFormat = $createFromFormat;

        return $this;
    }

    /**
     * Set the preferred output timezone.
     *
     * This calculation is performed on a _best-effort_ basis and is not guaranteed. Factors which may affect the
     * calculation include:
     *
     * * the version of glibc/musl that your OS relies on
     * * the freshness of the timestamp data your OS relies on
     * * the format of the datestamp inside of the feed and PHP's ability to parse it
     *
     * @param string $timezone The timezone identifier to use. Must be compatible with `DateTimeZone`. The default
     *                         value is `UTC`.
     *
     * @return self
     */
    public function setOutputTimezone(string $timezone = 'UTC'): self
    {
        $this->outputTimezone = $timezone;

        return $this;
    }
}
