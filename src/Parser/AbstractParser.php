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
}
