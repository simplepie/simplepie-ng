# SimplePie “NG”

**Highly experimental. Don't use this.**

**SimplePie NG** is a modern, _next-generation_ PHP package for working with syndication feeds. It was re-written from the ground-up to take advantage of the changes that have happened in the PHP community since SimplePie was first released in July 2004 for PHP 4.3.


## Features (planned)

* Built for professional-grade software engineers.
* Requires PHP 7.1+. Maybe 7.2 before we're done.
* Complies mith multiple PSR recommendations and drafts.
* Supports multiple log levels.
* Written following a stricter view of the PSR coding style guidelines.
* Leverages a middleware-based system for feed format support and other add-ons.
* Support for feed formats can be as inclusive or exclusive as you want. You can choose how you want to spend your performance points.
* Will almost certainly not work with shared hosting providers. Time to get a grown-up server.


## Feed support through middleware (planned)

* [JSON Feed 1.0](https://jsonfeed.org/version/1)
* [RSS 1.0](http://web.resource.org/rss/1.0/spec)
* [RSS 2.0](http://www.rssboard.org/rss-specification)
* [Atom 1.0](https://tools.ietf.org/html/rfc4287)

### And XML grammars

* [RSS 1.0 Content Modules](http://web.resource.org/rss/1.0/modules/content/)
* [Dublin Core Metadata 1.0](http://dublincore.org/documents/1998/09/dces/) and [1.1](http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements)
* [Dublin Core Terms](http://dublincore.org/documents/2012/06/14/dcmi-terms/)
* [W3C WGS84 Geo](https://www.w3.org/2003/01/geo/)
* [GeoRSS (Simple)](http://www.georss.org/simple.html)
* [GeoRSS (GML)](http://www.georss.org/gml.html)
* [Media RSS](http://www.rssboard.org/media-rss)
* [iTunes Podcast RSS](https://help.apple.com/itc/podcasts_connect/#/itcb54353390)
* [Creative Commons](http://backend.userland.com/creativeCommonsRssModule)
* [W3C Annotation Schema](https://www.w3.org/2000/10/annotation-ns)
* [W3C Simple Knowledge Organization System](https://www.w3.org/2009/08/skos-reference/skos.html)
* [FOAF](http://xmlns.com/foaf/spec/)

### Might come later

* [h-feed](http://microformats.org/wiki/h-feed)/[h-entry](http://microformats.org/wiki/h-entry)
* Ancient RSS versions
* Atom 0.3
* [OpenSearch](http://www.opensearch.org/Specifications/OpenSearch/1.1/Draft_5)

### Other interesting things

* Twitter
* Other things that take on a post-like structure.
* [PubSubHubbub](https://blog.superfeedr.com/howto-pubsubhubbub/)
* [FeedSync](http://feedsyncsamples.codeplex.com/wikipage?title=FeedSync%20for%20Atom%20and%20RSS%20%28v1.0%29%20specification)


## Not the kitchen sink (PSRs)

Previously, SimplePie tried to do it all. In retrospect, this was a bad idea.

* The logging layer will support PSR-3 adapters.
* The caching layer will support PSR-6/PSR-16 adapters.
* The HTTP layer will support PSR-7 adapters.
* The dependency injection layer will support PSR-11 adapters.
* Middleware isn't HTTP middleware, so doesn't follow PSR-15, but is inspired by Guzzle's single-pass [Middleware HandlerStack](http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html#handlerstack).
