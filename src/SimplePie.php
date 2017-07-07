<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie;

use Psr\Http\Message\StreamInterface;
use SimplePie\Mixin\LibxmlTrait;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Mixin\MiddlewareStackTrait;
use SimplePie\Parser\Xml as XmlParser;

define('SIMPLEPIE_ROOT', __DIR__);

/**
 * `SimplePie\SimplePie` is the primary entry point for SimplePie NG.
 */
class SimplePie
{
    use LibxmlTrait;
    use LoggerTrait;
    use MiddlewareStackTrait;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        $this->logger = Configuration::getLogger();
        $this->logger->info(sprintf('`%s` has completed instantiation.', __CLASS__));
    }

    //---------------------------------------------------------------------------

    /**
     * Parses content which is known to be valid XML and is encoded as UTF-8.
     *
     * @param StreamInterface $stream                  A PSR-7 `StreamInterface` which is typically returned by the
     *                                                 `getBody()` method of a `ResponseInterface` class.
     * @param bool            $handleHtmlEntitiesInXml Whether or not SimplePie should pre-parse the XML as HTML to
     *                                                 resolve the entities. A value of `true` means that SimplePie
     *                                                 should inject the entity definitions. A value of `false` means
     *                                                 that SimplePie should NOT inject the entity definitions. The
     *                                                 default value is `false`.
     *
     * @return XmlParser
     */
    public function parseXml(StreamInterface $stream, bool $handleHtmlEntitiesInXml = false): XmlParser
    {
        $parser = new XmlParser($stream, $handleHtmlEntitiesInXml);

        return $parser;
    }

    /**
     * Parses content which is known to be valid JSON and is encoded as UTF-8.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by the
     *                                `getBody()` method of a `ResponseInterface` class.
     *
     * @return JsonParser
     */
    public function parseJson(StreamInterface $stream)
    {
        return $stream->getContents();
    }
}
