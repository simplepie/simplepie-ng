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
use ReflectionClass;
use SimplePie\Dictionary\Ns;
use SimplePie\Dom;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Enum\FeedType;
use SimplePie\Exception\ConfigurationException;
use SimplePie\Mixin\DomDocumentTrait;
use SimplePie\Mixin\RawDocumentTrait;
use SimplePie\SimplePie;
use SimplePie\Type\Feed;
use Throwable;

class Xml extends AbstractParser
{
    use DomDocumentTrait;
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
     * @param StreamInterface $stream                  A PSR-7 `StreamInterface` which is typically returned by the
     *                                                 `getBody()` method of a `ResponseInterface` class.
     * @param bool            $handleHtmlEntitiesInXml Whether or not SimplePie should pre-parse the XML as HTML to
     *                                                 resolve the entities. A value of `true` means that SimplePie
     *                                                 should inject the entity definitions. A value of `false` means
     *                                                 that SimplePie should NOT inject the entity definitions. The
     *                                                 default value is `false`.
     *
     * @throws Error
     * @throws TypeError
     * @throws ConfigurationException
     */
    public function __construct(StreamInterface $stream, bool $handleHtmlEntitiesInXml = false)
    {
        // Container
        $this->container = SimplePie::getContainer();

        // Raw stream
        $this->rawDocument = $this->readStream($stream);

        // DOMDocument
        $this->domDocument = new DOMDocument();

        // Handle registerNodeClass() calls
        foreach ($this->container['_.dom.extend._matches'] as $baseClass => $extendingClass) {
            try {
                if ((new ReflectionClass($extendingClass))->implementsInterface(DomInterface::class)) {
                    $this->domDocument->registerNodeClass(sprintf('DOM%s', $baseClass), $extendingClass);
                } else {
                    throw new ConfigurationException(sprintf(
                        ErrorMessage::DOM_NOT_EXTEND_FROM,
                        $baseClass,
                        $extendingClass,
                        $baseClass
                    ));
                }
            } catch (Throwable $e) {
                throw $e;
            }
        }

        libxml_use_internal_errors(true);

        // DOMDocument configuration
        $this->domDocument->recover             = true;
        $this->domDocument->formatOutput        = true;
        $this->domDocument->preserveWhiteSpace  = false;
        $this->domDocument->resolveExternals    = false;
        $this->domDocument->substituteEntities  = true;
        $this->domDocument->strictErrorChecking = false;

        // Do we need to parse as HTML first, then rewrite the source XML?
        if ($handleHtmlEntitiesInXml) {
            $this->domDocument->loadHTML($this->rawDocument, $this->container['_.dom.libxml']);
            $this->domDocument->normalizeDocument();
            $this->rawDocument = $this->domDocument->saveXML();
        }

        $this->domDocument->loadXML($this->rawDocument, $this->container['_.dom.libxml']);
        $this->domDocument->normalizeDocument();

        // Instantiate a new write-to feed object.
        $this->feed = new Feed();

        // Invoke the middleware.
        $this->container['_.middleware']->invoke(
            FeedType::XML,
            $this->getFeed()->getRoot(),
            $this->getNamespaceAlias(),
            $this->xpath()
        );
    }

    /**
     * Get the preferred namespace alias.
     *
     * @return string|null
     */
    public function getNamespaceAlias(): ?string
    {
        $namespace = new Ns($this->domDocument);

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
