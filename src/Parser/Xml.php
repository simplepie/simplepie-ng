<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Parser;

use DOMDocument;
use DOMXPath;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use SimplePie\Enum as E;
use SimplePie\HandlerStackInterface;
use SimplePie\Mixin as Tr;
use SimplePie\SimplePie;
use SimplePie\Type\Feed;
use SimplePie\Util\Ns;

/**
 * The core parser for all XML content.
 */
class Xml extends AbstractParser
{
    use Tr\DomDocumentTrait;
    use Tr\LoggerTrait;
    use Tr\RawDocumentTrait;

    /**
     * The object which contains the parsed results.
     *
     * @var Feed
     */
    protected $feed;

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
     * The XML namespace handler.
     *
     * @var Ns
     */
    protected $ns;

    /**
     * Constructs a new instance of this class.
     *
     * @param StreamInterface       $stream                  A PSR-7 `StreamInterface` which is typically returned by
     *                                                       the `getBody()` method of a `ResponseInterface` class.
     * @param LoggerInterface       $logger                  The PSR-3 logger.
     * @param HandlerStackInterface $handlerStack            The handler stack which contains registered middleware.
     * @param int                   $libxml                  The libxml value to use for parsing XML.
     * @param bool                  $handleHtmlEntitiesInXml Whether or not SimplePie should pre-parse the XML as HTML
     *                                                       to resolve the entities. A value of `true` means that
     *                                                       SimplePie should inject the entity definitions. A value of
     *                                                       `false` means that SimplePie should NOT inject the entity
     *                                                       definitions. The default value is `false`.
     *
     * @throws Error
     * @throws TypeError
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    public function __construct(
        StreamInterface $stream,
        LoggerInterface $logger,
        HandlerStackInterface $handlerStack,
        int $libxml,
        bool $handleHtmlEntitiesInXml
    ) {
        // phpcs:enable

        // Logger
        $this->logger = $logger;

        // Middleware
        $this->middleware = $handlerStack;

        // Libxml2
        $this->libxml = $libxml;

        // Raw stream
        $this->rawDocument = $this->readStream($stream);

        // DOMDocument
        $this->domDocument = new DOMDocument('1.0', 'utf-8');

        // Don't barf errors all over the output
        \libxml_use_internal_errors(true);

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
            $rootElementStart = \sprintf('<%s', (string) $this->domDocument->firstChild->nodeName);

            // Read the entity definition file, and force-inject it into the XML document
            $this->rawDocument = \str_replace(
                $rootElementStart,
                \sprintf(
                    '%s%s',
                    \trim(
                        \file_get_contents(\dirname(SIMPLEPIE_ROOT) . '/resources/entities.dtd')
                    ),
                    $rootElementStart
                ),
                $this->rawDocument
            );
        }

        // Parse the XML document with the configured libxml options
        $this->domDocument->loadXML($this->rawDocument, $this->libxml);

        // Register the namespace handler.
        $this->ns = (new Ns($this->domDocument))
            ->setLogger($this->getLogger());

        // Look at which namespaces the registered middleware understands.
        $this->middleware->registerNamespaces($this->ns);

        // Instantiate a new write-to feed object.
        $this->feed = (new Feed($this->getNamespaceAlias() ?? ''))
            ->setLogger($this->getLogger());

        // Invoke the registered middleware.
        $this->middleware->invoke(
            E\FeedType::XML,
            $this->getFeed()->getRoot(),
            $this->getNamespaceAlias(),
            $this->xpath()
        );

        // Clear the libxml errors to avoid excessive memory usage
        \libxml_clear_errors();
    }

    /**
     * Get the XML namespace handler.
     *
     * @return Ns
     */
    public function getNs(): Ns
    {
        return $this->ns;
    }

    /**
     * Get the preferred namespace alias.
     *
     * @return string|null
     */
    public function getNamespaceAlias(): ?string
    {
        $namespace = $this->getNs();

        return $namespace->getPreferredNamespaceAlias(
            $this->domDocument->documentElement->namespaceURI
        );
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
        if (null !== $ns) {
            $xpath->registerNamespace(
                $ns,
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
