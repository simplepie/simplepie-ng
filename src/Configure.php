<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Exception\ConfigurationException;
use Skyzyx\UtilityPack\Types;

class Configure
{
    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // The PSR-3 logger
        if ($container->has('__sp__.logger')) {
            if ($container['__sp__.logger'] instanceof LoggerInterface) {
                $this->logger = $container['__sp__.logger'];
            } else {
                throw new ConfigurationException(
                    sprintf(ErrorMessage::LOGGER_NOT_PSR3, Types::getClassOrType($container['__sp__.logger']))
                );
            }
        } else {
            $this->logger = new NullLogger();
        }
    }

    /**
     * Parses a PSR-7 message to determine information about the data.
     *
     * @param MessageInterface $message     A PSR-7 message, which responds to the `MessageInterface` interface.
     * @param string|null      $contentType The Content-Type that you want the data to be force-processed as. The
     *                                      default value is `null`, which will trigger an introspection of the message
     *                                      for this data.
     * @param string|null      $charset     The character set that you want the data to be force-processed as. The
     *                                      default value is `null`, which will trigger an introspection of the message
     *                                      for this data.
     *
     * @return [type] [description]
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
