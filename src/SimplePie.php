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
use Psr\Http\Message\StreamInterface;
use SimplePie\Mixin\ContainerTrait;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Parser\Xml as XmlParser;

class SimplePie
{
    use ContainerTrait;
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(Configure $configuration)
    {
        $this->container = $configuration->getContainer();
        $this->logger    = $configuration->getLogger();
    }

    /**
     * Parses content which is known to be valid XML and is encoded as UTF-8.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by the
     *                                `getBody()` method of a `ResponseInterface` class.
     *
     * @return [type] [description]
     */
    public function parseXml(StreamInterface $stream)
    {
        $parser = new XmlParser($stream);

        return $parser->getRawDocument();
    }

    public function parseJson(StreamInterface $stream)
    {
        return $stream->getContents();
    }
}
