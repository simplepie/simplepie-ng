<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie;

use Psr\Http\Message\StreamInterface;
use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\Mixin as Tr;
use SimplePie\Parser\Xml as XmlParser;

\define('SIMPLEPIE_ROOT', __DIR__);

/**
 * `SimplePie\SimplePie` is the primary entry point for SimplePie NG.
 */
class SimplePie implements C\SetLoggerInterface
{
    use Tr\LoggerTrait;

    /**
     * Bitwise libxml options to use for parsing XML.
     *
     * @var int
     */
    protected $libxml;

    /**
     * The handler stack which contains registered middleware.
     *
     * @var HandlerStackInterface
     */
    protected $middleware;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        // Default logger
        $this->logger = new NullLogger();

        // Default middleware stack
        $this->middleware = new HandlerStack();
        $this->middleware->setLogger($this->getLogger());
        $this->middleware->append(new Atom(), 'atom');

        // Default libxml2 settings
        $this->libxml = LIBXML_HTML_NOIMPLIED // Required, or things crash.
            | LIBXML_BIGLINES
            | LIBXML_COMPACT
            | LIBXML_HTML_NODEFDTD
            | LIBXML_NOBLANKS
            | LIBXML_NOENT
            | LIBXML_NOXMLDECL
            | LIBXML_NSCLEAN
            | LIBXML_PARSEHUGE;
    }

    /**
     * Sets the libxml value to use for parsing XML.
     *
     * @param int $libxml
     *
     * @return int
     */
    public function setLibxml(int $libxml)
    {
        $this->libxml = $libxml;

        // What are the libxml2 configurations?
        $this->logger->debug(\sprintf(
            'Libxml configuration has a bitwise value of `%s`.%s',
            $this->libxml,
            (4792582 === $this->libxml)
                ? ' (This is the default configuration.)'
                : ''
        ));

        return $this;
    }

    /**
     * Gets the libxml value to use for parsing XML.
     *
     * @return int
     */
    public function getLibxml(): int
    {
        return $this->libxml;
    }

    /**
     * Sets the handler stack which contains registered middleware.
     *
     * @param HandlerStackInterface $handlerStack
     *
     * @return self
     */
    public function setMiddlewareStack(HandlerStackInterface $handlerStack)
    {
        $this->middleware = $handlerStack;
        $this->middleware->setLogger($this->getLogger());

        return $this;
    }

    /**
     * Gets the handler stack which contains registered middleware.
     *
     * @return HandlerStackInterface
     */
    public function getMiddlewareStack(): HandlerStackInterface
    {
        return $this->middleware;
    }

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
        $parser = new XmlParser(
            $stream,
            $this->logger,
            $this->middleware,
            $this->libxml,
            $handleHtmlEntitiesInXml
        );

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
