<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Dictionary;

use DOMNode;
use SimplePie\Configuration;
use SimplePie\Mixin\LoggerTrait;

/**
 * Provides tools for managing and working with XML namespaces.
 *
 * @todo Separate the enums and migrate this into a utility class.
 */
class Ns
{
    use LoggerTrait;

    public const ATOM_03 = 'http://purl.org/atom/ns#';
    public const ATOM_10 = 'http://www.w3.org/2005/Atom';
    public const RDF     = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
    public const RSS_090 = 'http://my.netscape.com/rdf/simple/0.9/';
    public const RSS_10  = 'http://purl.org/rss/1.0/';
    public const RSS_20  = '';
    public const XHTML   = 'http://www.w3.org/1999/xhtml';
    public const XML     = 'http://www.w3.org/XML/1998/namespace';

    // http://purl.org/rss/1.0/modules/content/
    // http://purl.org/dc/elements/1.0/
    // http://purl.org/dc/elements/1.1/
    // http://www.w3.org/2003/01/geo/wgs84_pos#
    // http://www.georss.org/georss
    // http://search.yahoo.com/mrss/
    // http://search.yahoo.com/mrss
    // http://video.search.yahoo.com/mrss
    // http://video.search.yahoo.com/mrss/
    // http://www.itunes.com/dtds/podcast-1.0.dtd

    /**
     * A mapping of namespace URIs to preferred namespace aliases.
     *
     * @var array
     */
    protected $mapping = [];

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode $dom A DOMDocument object representing the XML file to be parsed.
     */
    public function __construct(DOMNode $dom)
    {
        $this->logger      = Configuration::getLogger();
        $this->domDocument = $dom;

        $this->mapping = [
            static::ATOM_03 => 'atom03',
            static::ATOM_10 => 'atom10',
            static::RDF     => 'rdf',
            static::RSS_090 => 'rss09',
            static::RSS_10  => 'rss10',
            static::RSS_20  => 'rss20',
            static::XHTML   => 'xhtml',
            static::XML     => 'xml',
        ];
    }

    /**
     * Adds new aliases to this list of aliases. Set an associative array where
     * the key is the namespace URI and the namespace alias as the value.
     *
     * @param array $aliases An associative array of namespace URIs to namespace aliases.
     *
     * @return array The updated list of namespace aliases.
     */
    public function addAliases(array $aliases): array
    {
        $this->mapping = array_merge($this->mapping, $aliases);
        $this->getLogger()->info('Added namespace URIs and namespace aliases.', $this->mapping);

        return $this->mapping;
    }

    /**
     * Gets the list of document-defined namespace aliases and namespace URIs.
     *
     * @return array
     */
    public function getDocNamespaces(): array
    {
        return simplexml_import_dom($this->domDocument)->getDocNamespaces(true, true);
    }

    /**
     * Gets the preferred namespace alias for a particular feed dialect.
     *
     * @param string|null $namespaceUri The namespace URI used inside the XML document. If the value is `null`, then
     *                                  the preferred namespace alias of the root namespace will be returned. The
     *                                  default value is `null`.
     *
     * @return string|null
     */
    public function getPreferredNamespaceAlias(?string $namespaceUri = null): ?string
    {
        $namespaceUri = $namespaceUri
            ?? $this->domDocument->documentElement->namespaceUri;

        if (isset($this->mapping[$namespaceUri])) {
            return $this->mapping[$namespaceUri];
        }

        return null;
    }
}
