<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Parser;

use DOMDocument;
use DOMXPath;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use SimplePie\Dictionary\Ns;
use SimplePie\Enum\FeedType;
use SimplePie\Exception\ConfigurationException;
use SimplePie\HandlerStackInterface;
use SimplePie\Mixin\DomDocumentTrait;
use SimplePie\Mixin\LibxmlTrait;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Mixin\MiddlewareStackTrait;
use SimplePie\Mixin\RawDocumentTrait;
use SimplePie\SimplePie;
use SimplePie\Type\Feed;

class Xml extends AbstractParser
{
    use DomDocumentTrait;
    use LibxmlTrait;
    use LoggerTrait;
    use MiddlewareStackTrait;
    use RawDocumentTrait;

    /**
     * The object which contains the parsed results.
     *
     * @var Feed
     */
    protected $feed;

    /**
     * Constructs a new instance of this class.
     *
     * @param LoggerInterface       $logger                  A PSR-3 logger.
     * @param HandlerStackInterface $middleware              A middleware handler stack containing any registered middleware.
     * @param StreamInterface       $stream                  A PSR-7 `StreamInterface` which is typically returned by the
     *                                                       `getBody()` method of a `ResponseInterface` class.
     * @param bool                  $handleHtmlEntitiesInXml Whether or not SimplePie should pre-parse the XML as HTML to
     *                                                       resolve the entities. A value of `true` means that SimplePie
     *                                                       should inject the entity definitions. A value of `false` means
     *                                                       that SimplePie should NOT inject the entity definitions. The
     *                                                       default value is `false`.
     * @param int                   $libxml                  A set of bitwise LIBXML_* constants.
     *
     * @throws Error
     * @throws TypeError
     * @throws ConfigurationException
     *
     * @codingStandardsIgnoreStart
     */
    public function __construct(
        LoggerInterface $logger,
        HandlerStackInterface $middleware,
        StreamInterface $stream,
        bool $handleHtmlEntitiesInXml,
        int $libxml
    ) {
        // @codingStandardsIgnoreEnd

        // Logger
        $this->logger = $logger;

        // Libxml settings
        $this->libxml = $libxml;

        // Middleware stack
        $this->middleware = $middleware;

        // Raw stream
        $this->rawDocument = $this->readStream($stream);

        // DOMDocument
        $this->domDocument = new DOMDocument();

        // Don't barf errors all over the output
        libxml_use_internal_errors(true);

        // DOMDocument configuration
        $this->domDocument->recover             = true;
        $this->domDocument->formatOutput        = true;
        $this->domDocument->preserveWhiteSpace  = false;
        $this->domDocument->resolveExternals    = true;
        $this->domDocument->substituteEntities  = true;
        $this->domDocument->strictErrorChecking = false;
        $this->domDocument->validateOnParse     = false;

        // If enabled, force-inject the contents of `entities.dtd` into the feed.
        if ($handleHtmlEntitiesInXml) {
            $this->getLogger()->debug('Enabled handing HTML entities in XML.');
            $this->domDocument->loadXML($this->rawDocument, $this->libxml);

            // <feed, <rss, etc.
            $rootElementStart = sprintf('<%s', (string) $this->domDocument->firstChild->nodeName);

            // Read the entity definition file, and force-inject it into the XML document
            $this->rawDocument = str_replace(
                $rootElementStart,
                sprintf('%s%s', trim(file_get_contents(SIMPLEPIE_ROOT . '/entities.dtd')), $rootElementStart),
                $this->rawDocument
            );
        }

        // Parse the XML document with the configured libxml options
        $this->domDocument->loadXML($this->rawDocument, $this->libxml);

        // Instantiate a new write-to feed object.
        $this->feed = new Feed($this->logger, $this->getNamespaceAlias());

        // Invoke the registered middleware.
        $this->middleware->invoke(
            FeedType::XML,
            $this->getFeed()->getRoot(),
            $this->getNamespaceAlias(),
            $this->xpath()
        );

        // Clear the libxml errors to avoid excessive memory usage
        libxml_clear_errors();
    }

    /**
     * Get the preferred namespace alias.
     *
     * @return string|null
     */
    public function getNamespaceAlias(): ?string
    {
        $namespace = new Ns($this->logger, $this->domDocument);

        $alias = $namespace->getPreferredNamespaceAlias(
            $this->domDocument->documentElement->namespaceURI
        );

        return $alias;
    }

    /**
     * Gets a reference to the `DOMXPath` object, with the default namespace
     * already registered.
     *
     * @return DOMXPath
     */
    public function xpath()
    {
        $ns    = $this->getNamespaceAlias();
        $xpath = new DOMXPath($this->domDocument);

        // Register the namespace alias with the XPath instance
        if (!is_null($ns)) {
            $xpath->registerNamespace($ns,
                $this->domDocument->documentElement->namespaceURI ?? ''
            );
        }

        return $xpath;
    }

    /**
     * Retrieves the object which represents the top-level feed.
     *
     * @return Feed
     */
    public function getFeed(): Feed
    {
        return $this->feed;
    }
}
