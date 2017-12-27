# XML Parsing

XML ([REC-xml-20081126]) is the intended serialization of the [Atom] and [RSS] formats. As such, SimplePie NG has been designed from the beginning with support for multiple feed serialization types.

## High-Level Internals

Internally, we use the [DOMDocument] family of classes to parse the XML data into an object. The internal XML parser is [libxml2] which implements the spec, but also has some niceties such as the ability to [recover from malformed XML](https://secure.php.net/manual/en/class.domdocument.php#domdocument.props.recover). This is a tremendous improvement over SimplePie OG’s usage of the [XML Parser](http://php.net/manual/en/ref.xml.php) functions leftover from PHP 4.

## HTML Entities

There are (generally) two kinds of entities: (a) _named_ entities (e.g., `&nbsp;`) and (b) _numeric_ entities (e.g., `&#xA0;` or `&#160;`).

Most of the _named entities_ defined in HTML are _not_ defined in XML. This means that unless the feed author (or the feed-generating software) is diligent about using CDATA to wrap these named entities, most XML parsers will fail. (This was a problem that plagued SimplePie OG, and was the source of a tremendous amount of workarounds in the source code.)

SimplePie NG handles things differently. Firstly, it uses the [DOMDocument] family of classes which are higher-level than the old XML Parser code. Secondly, since [libxml2] offers a recovery bit, it will continue parsing the document even if it encounters these kinds of errors. Thirdly, we now fetch and maintain a [canonical list of entity mappings defined for HTML5](https://www.w3.org/TR/html5/entities.json) from the W3C, that we can use to handle un-CDATA'd named entities in XML.

At present, this named entity handling is disabled by default for two reasons: (a) we don't yet have enough test coverage to help us avoid [XML External Entity][XXE] vulnerabilities, and (b) because this involves us modifying the XML on the fly to insert our entity definitions (which is slightly slower).

## XML Namespaces

SimplePie NG leverages the `DOMDocument` class to parse XML. A big part of parsing XML is working with elements that are namespaced. We keep track of many [XML Namespace URIs](https://github.com/simplepie/simplepie-ng/wiki/XML-Namespace-URIs) in our wiki. For the most part, these URIs are applied in a standard way across all feeds.

However, there are a few cases where the authors of the specification were inconsistent in the naming of their XML Namespace URI, and various edits of the specification over the years resulted in feeds with various URIs for the same specification. This was troublesome for SimplePie OG, which attempted to map elements to well-known URIs. One of the biggest offenders was Yahoo! with the [Media RSS spec](https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS), who gave the spec three different URIs over the course of Yahoo!'s ownership.

SimplePie NG addresses this differently. All feed formats are processed using middleware, and each XML-based middleware registers the XML Namespace URIs it understands. This is an array, which means that you can register multiple URIs. When SimplePie parses a feed, it executes the middleware stack to determine how to handle it. When the middleware is executed, it attempts to match the feed's XML Namespace URI against what it understands. If there is an exact match in the list, we know that we can parse it. If we don't get an exact match, SimplePie tries to match again as though the middleware-provided URI were a regex.

If a middleware handler is not found, the namespaced elements are not parsed.

## XPath 1.0

As part of leveraging `DOMDocument` for XML handling, we have access to the [DOMXPath](http://php.net/manual/en/class.domxpath.php) class. PHP's XPath processing of XML data is _substantially_ more performant than looping over PHP arrays in userland code. As such, all first-party middleware uses XPath expressions for retrieving data from feeds.

> **NOTE:** Despite the fact that [XPath 2.0](https://www.w3.org/TR/xpath20/), [3.0](https://www.w3.org/TR/xpath-30/), and [3.1](https://www.w3.org/TR/xpath-31/) exist, PHP is limited to [XPath 1.0](https://www.w3.org/TR/xpath/).

The `AbstractXmlMiddleware` class provided by SimplePie NG has methods which support case-insensitive XPath expressions, meaning that `<pubDate>`, `<pubdate>`, and `<PubDate>` will all resolve correctly with the same XPath expression.

Custom, third-party XML-based middleware is encouraged to extend this class and leverage these methods for the broadest-possible compatibility. Improvements made to the underlying methods will automatically improve any extending middleware.

.. reviewer-meta::
   :written-on: 2017-12-17
   :proofread-on: 2017-12-26

  [Atom]: https://tools.ietf.org/html/rfc4287
  [DOMDocument]: http://php.net/manual/en/class.domdocument.php
  [libxml2]: http://xmlsoft.org
  [REC-xml-20081126]: https://www.w3.org/TR/2008/REC-xml-20081126/
  [RFC 4287]: https://tools.ietf.org/html/rfc4287
  [RSS]: http://www.rssboard.org/rss-specification
  [XXE]: https://www.owasp.org/index.php/XML_External_Entity_(XXE)_Processing
