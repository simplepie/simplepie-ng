<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Parser;

use DateTime;
use DateTimeZone;

/**
 * The core parser for all Date content.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#33-date-constructs
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#429-the-atompublished-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#4215-the-atomupdated-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#elements-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#items
 */
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
     * @param string|null $datestamp        The datestamp to handle, as a string. The default value is `null`.
     * @param string|null $outputTimezone   The timezone identifier to use. Must be compatible with `DateTimeZone`.
     *                                      The default value is `UTC`.
     * @param string|null $createFromFormat Allows the user to assist the date parser by providing the input format of
     *                                      the datestamp. This will be passed into `DateTime::createFromFormat()`
     *                                      at parse-time.
     *
     * @see http://php.net/manual/en/datetime.createfromformat.php
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    public function __construct(
        ?string $datestamp = null,
        ?string $outputTimezone = 'UTC',
        ?string $createFromFormat = null
    ) {
        // phpcs:enable

        $this->datestamp        = $datestamp ?? null;
        $this->createFromFormat = $createFromFormat;

        if (null !== $this->datestamp) {
            // Convert null to UTC; Convert Z to UTC.
            if (null === $outputTimezone) {
                $this->outputTimezone = 'UTC';
            } elseif ('Z' === \mb_strtoupper($outputTimezone)) {
                $this->outputTimezone = 'UTC';
            } else {
                $this->outputTimezone = $outputTimezone;
            }

            // Use the custom formatter, if available
            if (null !== $this->createFromFormat) {
                $this->dateTime = DateTime::createFromFormat(
                    $this->createFromFormat,
                    $this->datestamp,
                    new DateTimeZone($this->outputTimezone)
                );
            } else {
                $this->dateTime = new DateTime(
                    $this->datestamp,
                    new DateTimeZone($this->outputTimezone)
                );
            }

            // Sometimes, `createFromFormat()` doesn't set this correctly.
            $this->dateTime->setTimezone(new DateTimeZone($this->outputTimezone));
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
    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }
}
