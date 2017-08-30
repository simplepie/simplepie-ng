# SimplePie “NG”

**Highly experimental. Don't use this.**

**SimplePie NG** is a modern, _next-generation_ PHP package for working with syndication feeds. It was re-written from the ground-up to take advantage of the changes that have happened in the PHP community since SimplePie was first released in July 2004 for PHP 4.3.


## How can I contribute?

**You can't** — at least, not yet. I'm still in the _big-bang_ (or _iteration-zero_, or _proof-of-concept_) phase, and I'm not _yet_ looking for code contributions. I'm making progress on the ideas that I have, and things are still wildly experimental and unstable.

If, after reading the existing docs, you have questions or ideas, you can file a feature request or start a discussion thread in GitHub Issues. I'm willing to entertain good ideas provided that they are good for professional-grade software engineers. My goal is _mostly_ spec-compliance, but adapted to the realities of real-world feeds.


## What is SimplePie NG, _really?_

**SimplePie [OG](http://www.urbandictionary.com/define.php?term=OG)** was created for PHP 4.3 by a person who was brand-new to programming. That was me. About 6 months into the project, I started receiving a _tremendous_ amount of help from a teenage Scot who had nothing better to do — thanks Geoffrey!

Over the years I've tried to start this project by forking SimplePie and stripping out as much legacy code as possible. Those efforts never made it very far. The original codebase grew rapidly and organically over the first 5 years (before I got burned-out and retired from SimplePie development), is extremely complex, not tested or documented nearly well enough, and has been kept alive since 2010 by way of Frankenstein-like patchwork. As PHP and its community have matured over the years, I've started to experiment with new approaches to handling such a complex set of tasks as SimplePie performs.

**SimplePie NG** is a from-scratch rewrite of SimplePie for PHP 7.1. It starts with a completely different kind of thinking, and more than a decade more experience in software engineering and open-source. It is written with a view of PHP from 2017 and beyond, and is being built in such a way that greater community involvement should be far easier from much earlier in the project's life.

I want this tool. I have a deep knowledge about feeds. I am working to make this happen.


## Features (planned)

* Built for professional-grade software engineers.
* Requires PHP 7.1+. Maybe 7.2 before we're done.
* Complies mith multiple PSR recommendations and drafts.
* Supports multiple log levels.
* Written following a stricter view of the PSR coding style guidelines.
* Leverages a middleware-based system for feed format support and other add-ons.
* Support for feed formats can be as inclusive or exclusive as you want. You can choose how you want to spend your performance points.

### Non-Goals

* Backwards-compatibility with SimplePie 1.x.
* Compatibility with older/historic versions of PHP. We're starting with 7.1/7.2 and moving forward from there.
* Will almost certainly not work with shared hosting providers. Time to get a grown-up server.
* WordPress compatibility.


## Examples

**Still in the proof-of-concept phase.** PSR-3 logging, and using PSR-7 for feed transport.

There will probably be a wrapper for `parseXml()`, `parseJson()`, and `parseHtml()` in the form of `parse()`. There will also be some level of PSR-6/16 caching at the object layer to make non-first runs even faster.

### Simple, leveraging default options

```php
use GuzzleHttp\Psr7;
use SimplePie\SimplePie;

// Instantiate SimplePie.
$simplepie = new SimplePie();

// Pass a PSR-7 stream to SimplePie for parsing.
$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));

// Specifically parses XML.
$parser = $simplepie->parseXml($stream, true);

// Reference the top-level feed object
$feed = $parser->getFeed();

// Use familiar syntax to read the data you care about.
echo $feed->getTitle();
foreach ($feed->getItems() as $item) {
    echo $item->getTitle();
}
```


### Custom logger and middleware stack

```php
use GuzzleHttp\Psr7;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SimplePie\Configuration;
use SimplePie\Container;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Json\JsonFeed;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\Middleware\Xml\Rss;
use SimplePie\SimplePie;

// Instantiate your PSR-11 container. We'll use SimplePie\Container for this.
$container = new Container();

// Configure a simple PSR-3 logger.
$container['simplepie.logger'] = function () {
    $logger = new Logger('SimplePie');
    $logger->pushHandler(new ErrorLogHandler(
        ErrorLogHandler::OPERATING_SYSTEM,
        LogLevel::DEBUG,
        true,
        false
    ));

    return $logger;
};

// Configure the middleware stack.
$container['simplepie.middleware'] = function () {
    return $stack = (new HandlerStack())
        ->append(new JsonFeed(), 'jsonfeed')
        ->append(new Atom()    , 'atom10')
        ->append(new Rss()     , 'rss20')
    ;
};

// Lock-in your configuration. Resolve any default values.
Configuration::setContainer($container);

// Instantiate SimplePie.
$simplepie = new SimplePie();

// Pass a PSR-7 stream to SimplePie for parsing.
$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));

// Specifically parses XML.
// Only applies the middleware that is registered as supporting XML feed types.
$parser = $simplepie->parseXml($stream, true);

// Reference the top-level feed object
$feed = $parser->getFeed();

// By default, all nodes return a `SimplePie\Type\Node` object with a `toString()` method defined.
// You can also handle various allowed serializations differently.
foreach ($feed->getItems() as $item) {
    switch ($item->getTitle()->getSerialization()) {
        case 'xhtml':
            // Custom XML stuff...
            break;
        case 'text':
        case 'html':
        default:
            echo $item->getTitle()->getValue();
    }
}
```


## Feed support through middleware (planned)

* [ ] [JSON Feed 1.0](https://jsonfeed.org/version/1)
* [ ] [RSS 1.0](http://web.resource.org/rss/1.0/spec)
* [ ] [RSS 2.0](http://www.rssboard.org/rss-specification)
* [ ] [Atom 1.0](https://tools.ietf.org/html/rfc4287) (in-progress)

### And XML grammars

* [ ] [RSS 1.0 Content Modules](http://web.resource.org/rss/1.0/modules/content/)
* [ ] [Dublin Core Metadata 1.0](http://dublincore.org/documents/1998/09/dces/)
* [ ] [Dublin Core Metadata 1.1](http://dublincore.org/documents/1999/07/02/dces/)
* [ ] [Dublin Core Terms](http://dublincore.org/documents/2012/06/14/dcmi-terms/)
* [ ] [W3C WGS84 Geo](https://www.w3.org/2003/01/geo/)
* [ ] [GeoRSS (Simple)](http://www.georss.org/simple.html)
* [ ] [GeoRSS (GML)](http://www.georss.org/gml.html)
* [ ] [Media RSS](http://www.rssboard.org/media-rss)
* [ ] [iTunes Podcast RSS](https://help.apple.com/itc/podcasts_connect/#/itcb54353390)
* [ ] [Creative Commons](http://backend.userland.com/creativeCommonsRssModule)
* [ ] [W3C Annotation Schema](https://www.w3.org/2000/10/annotation-ns)
* [ ] [W3C Simple Knowledge Organization System](https://www.w3.org/2009/08/skos-reference/skos.html)
* [ ] [FOAF](http://xmlns.com/foaf/spec/)

### Might come later

* [ ] [h-feed](http://microformats.org/wiki/h-feed)/[h-entry](http://microformats.org/wiki/h-entry)
* [ ] Ancient RSS versions
* [ ] Atom 0.3
* [ ] [OpenSearch](http://www.opensearch.org/Specifications/OpenSearch/1.1/Draft_5)

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


## Services to integrate code with

* [X] https://waffle.io/simplepie/simplepie-ng
* [X] https://insight.sensiolabs.com/projects/1b772338-fd6a-4af1-8f5e-fddffc2b9d43
* [X] https://scrutinizer-ci.com/g/simplepie/simplepie-ng/
* [X] https://coveralls.io/github/simplepie/simplepie-ng
* [X] https://app.fossa.io/projects/git%2Bhttps%3A%2F%2Fgithub.com%2Fsimplepie%2Fsimplepie-ng/
* [X] https://pullapprove.com/simplepie/simplepie-ng/
* [X] https://sideci.com/gh/92493013/news_feed
* [X] https://zappr.opensource.zalan.do/repository/simplepie/simplepie-ng
* [X] https://gratipay.com/~skyzyx/
* [X] https://cla-assistant.io
* [ ] https://packagist.org
* [ ] https://travis-ci.org
* [ ] https://codeclimate.com


## Coding Standards

PSR-1/2/12 are a solid foundation, but are not an entire coding style by themselves. I have taken the time to document all of the nitpicky patterns and nuances of my personal coding style. It goes well-beyond brace placement and tabs vs. spaces to cover topics such as docblock annotations, ternary operations and which variation of English to use. It aims for thoroughness and pedanticism over hoping that we can all get along.

<https://github.com/skyzyx/php-coding-standards>
