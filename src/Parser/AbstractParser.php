<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Parser;

use Psr\Http\Message\StreamInterface;

/**
 * The base parser class that all other parser classes extend from. It handles low-level functionality that is shared across all parser classes.
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * Returns an opaque string representing the object.
     *
     * Note: Use of MD5 here is not cryptographically significant.
     *
     * @return string
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString(): string
    {
        return \sprintf('<%s: resource %s>', \get_called_class(), \md5(\spl_object_hash($this)));
    }

    /**
     * Reads the contents of the stream resource.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by the
     *                                `getBody()` method of a `ResponseInterface` class.
     *
     * @return string The raw contents of the steam resource.
     */
    public function readStream(StreamInterface $stream): string
    {
        return $stream->getContents();
    }
}
