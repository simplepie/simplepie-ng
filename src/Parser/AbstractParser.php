<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Parser;

use Psr\Http\Message\StreamInterface;

abstract class AbstractParser implements ParserInterface
{
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

    /**
     * Returns an opaque string representing the object.
     *
     * @return string
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString(): string
    {
        return sprintf('<%s %s>', get_called_class(), spl_object_hash($this));
    }
}
