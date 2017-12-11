<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Parser;

use DateTime;
use DateTimeZone;

class Date
{
    /**
     * The input datestamp.
     *
     * @var string
     */
    protected $datestamp;

    /**
     * The desired output timezone.
     *
     * @var string|null
     */
    protected $outputTimezone;

    /**
     * The format used to assist date string parsing.
     *
     * @var string|null
     */
    protected $createFromFormat;

    /**
     * The resulting `DateTime` object.
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Constructs a new instance of this class.
     *
     * Timezone calculation is performed on a _best-effort_ basis and is not guaranteed. Factors which may affect the
     * calculation include:
     *
     * * the version of glibc/musl that your OS relies on.
     * * the freshness of the timestamp data your OS relies on.
     * * the format of the datestamp inside of the feed and PHP's ability to parse it.
     *
     * @param string      $datestamp        The datestamp to handle, as a string.
     * @param string|null $outputTimezone   The timezone identifier to use. Must be compatible with `DateTimeZone`.
     *                                      The default value is `UTC`.
     * @param string|null $createFromFormat Allows the user to assist the date parser by providing the input format of
     *                                      the datestamp. This will be passed into `DateTime::createFromFormat()`
     *                                      at parse-time.
     *
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    public function __construct(string $datestamp, ?string $outputTimezone = 'UTC', ?string $createFromFormat = null)
    {
        $this->datestamp        = $datestamp;
        $this->outputTimezone   = $outputTimezone;
        $this->createFromFormat = $createFromFormat;

        // Use the custom formatter, if available
        if (null !== $createFromFormat) {
            $this->dateTime = DateTime::createFromFormat(
                $createFromFormat,
                $datestamp,
                new DateTimeZone($outputTimezone ?? 'UTC')
            );
        } else {
            $this->dateTime = new DateTime(
                $datestamp,
                new DateTimeZone($outputTimezone ?? 'UTC')
            );
        }
    }

    /**
     * Get the input datestamp.
     *
     * @return string
     */
    public function getDatestamp(): string
    {
        return $this->datestamp;
    }

    /**
     * Get the requested output timezone.
     *
     * @return string
     */
    public function getOutputTimezone(): string
    {
        return $this->outputTimezone;
    }

    /**
     * Get the format used to assist date string parsing.
     *
     * @return string|null
     */
    public function getCreateFromFormat(): ?string
    {
        return $this->createFromFormat;
    }

    /**
     * Get the resulting `DateTime` object.
     *
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
