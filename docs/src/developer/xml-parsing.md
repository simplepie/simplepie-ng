# XML Parsing (Draft)

XML ([REC-xml-20081126]) is the intended serialization of the [Atom] and [RSS] formats. As such, SimplePie NG has been designed from the beginning with support for multiple feed serialization types.

## High-Level Internals

Internally, we use the [DOMDocument] family of classes to parse the XML data into an object. The internal XML parser is [libxml2] which implements the spec, but also has some niceties such as the ability to [recover from malformed XML](https://secure.php.net/manual/en/class.domdocument.php#domdocument.props.recover). This is a tremendous improvement over SimplePie OG’s usage of the [XML Parser](http://php.net/manual/en/ref.xml.php) functions leftover from PHP 4.

## HTML Entities

There are (generally) two kinds of entities: (a) _named_ entities (e.g., `&nbsp;`) and (b) _numeric_ entities (e.g., `&#xA0;` or `&#160;`).

Most of the _named entities_ defined in HTML are _not_ defined in XML. This means that unless the feed author (or the feed-generating software) is diligent about using CDATA to wrap these named entities, most XML parsers will fail. (This was a problem that plagued SimplePie OG, and was the source of a tremendous amount of workarounds in the source code.)

SimplePie NG handles things differently. Firstly, it uses the [DOMDocument] family of classes which are higher-level than the old XML Parser code. Secondly, since [libxml2] offers a recovery bit, it will continue parsing the document even if it encounters these kinds of errors. Thirdly, we now fetch and maintain a [canonical list of entity mappings defined for HTML5](https://www.w3.org/TR/html5/entities.json) from the W3C, that we can use to handle un-CDATA'd named entities in XML.

At present, this named entity handling is disabled by default for two reasons: (a) we don't yet have enough test coverage to help us avoid [XML External Entity][XXE] vulnerabilities, and (b) because this involves us modifying the XML on the fly to insert our entity definitions (which is slightly slower).

## XML Namespaces

TBD.

## XPath 1.0

TBD.

.. reviewer-meta::
   :written-on: 2017-12-17
   :proofread-on: 2017-12-17

  [Atom]: https://tools.ietf.org/html/rfc4287
  [DOMDocument]: http://php.net/manual/en/class.domdocument.php
  [libxml2]: http://xmlsoft.org
  [REC-xml-20081126]: https://www.w3.org/TR/2008/REC-xml-20081126/
  [RFC 4287]: https://tools.ietf.org/html/rfc4287
  [RSS]: http://www.rssboard.org/rss-specification
  [XXE]: https://www.owasp.org/index.php/XML_External_Entity_(XXE)_Processing
