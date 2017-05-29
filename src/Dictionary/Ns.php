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
use ReflectionObject;

class Ns
{
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
     * The reflected data of this class.
     *
     * @var ReflectionObject
     */
    protected $reflected;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct(DOMNode $dom)
    {
        $this->reflected   = new ReflectionObject($this);
        $this->domDocument = $dom;
    }

    public function getNamespaces(): array
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

        $map = [
            static::ATOM_03 => 'atom03',
            static::ATOM_10 => 'atom10',
            static::RDF     => 'rdf',
            static::RSS_090 => 'rss09',
            static::RSS_10  => 'rss10',
            static::RSS_20  => 'rss20',
            static::XHTML   => 'xhtml',
            static::XML     => 'xml',
        ];

        if (isset($map[$namespaceUri])) {
            return $map[$namespaceUri];
        }

        return null;
    }
}
