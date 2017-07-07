<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Enum;

/**
 * Provides a set of known, allowable XML namespaces.
 */
class XmlNs extends AbstractEnum
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
}
