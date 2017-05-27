<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

namespace SimplePie;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\RejectedPromise;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SimplePie\Mixin\ContainerTrait;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Util\Negotiation;

class SimplePie
{
    use ContainerTrait;
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger    = $container['__sp__.logger'];
    }

    /**
     * Parses a PSR-7 message to determine information about the data.
     *
     * @param  MessageInterface $message     A PSR-7 message, which responds to the `MessageInterface` interface.
     * @param  string|null      $contentType The Content-Type that you want the data to be force-processed as. The
     *                                       default value is `null`, which will trigger an introspection of the message
     *                                       for this data.
     * @param  string|null      $charset     The character set that you want the data to be force-processed as. The
     *                                       default value is `null`, which will trigger an introspection of the message
     *                                       for this data.
     *
     * @return [type]                        [description]
     */
    public function parsePsr7Message(MessageInterface $message, ?string $contentType = null, ?string $charset = null)
    {
        return $this->parsePsr7Stream($message->getBody(), $contentType, $charset);
    }

    public function parsePsr7Stream(StreamInterface $stream, ?string $contentType = null, ?string $charset = null)
    {
        return $stream->getContents();
    }
}
