# Foundation

There are some core technology and design pattern choices which are foundational to SimplePie NG and how it has been built. Many of these decisions are based on lessons working with XML-based feeds being fetched over HTTP and cached.

## Lies, damned lies, and statistics

[Dr. House says that everybody lies](https://trakt.tv/shows/house). He's not wrong.

You know what else lies? Feeds. Web servers. Character encodings. Proxied HTTP requests. And pretty much everything else that drives feed syndication across the web.

Back in 2004, we learned this the hard way. I was cutting my teeth on PHP, XML, and RSS, and I learned that these things are nothing but liars. As a matter of fact, at one point Geoffrey and I estimated that over 70% of all RSS feeds were invalid XML. As a result, a tremendous percentage of code in the original SimplePie is designed for handling lies.

It wasn't until the [Go-PHP5 initiative](http://blog.birdhouse.org/2007/07/05/go-php5/) in 2007-2008 that we put our efforts into leaving behind PHP 4.3 and making PHP 5 a first-class citizen. Not only was PHP 5 more performant, but it also included niceties like `iconv` and `DOMDocument` out of the box. Unfortunately, we were targeting mostly shared hosting plans at that time, and needed to rely on `XMLReader` and other low-level XML parsing tools.

Now that we're targeting current versions of PHP, we can take advantage of some of the more powerful improvements in PHP.

## JSON

For the first time, SimplePie NG is expected to support the ability to query JSON documents. This is largely to support the new [JSON Feed](https://jsonfeed.org) specification, but also opens the doors to support other formats which translate nicely into associative arrays such as a theoretical YAML serialization of JSON Feed.

[JSON-D](https://wiki.php.net/rfc/jsond) in PHP 7 is faster than the previous JSON-C, and from the very beginning we're thinking about how to support non-XML feed formats.

## DOMDocument

PHP's [DOMDocument](http://php.net/DOMDocument) class is really excellent at parsing malformed XML. It understands things like CDATA blocks, doesn't explode when encountering HTML entities which aren't defined in XML, and has a lot of flexibility. It leverages `libxml2` under the hood, and has a much richer set of parsing tools in its toolbox compared to `SimpleXML`.

## XPath

[XPath](https://www.w3.org/TR/xpath/) is a method of querying XML documents for selected bits of data. In some informal tests, I have benchmarked XPath performing over _100 times faster_ than iterating over the same XML efficiently in PHP. PHP's XPath implementation is highly-tuned for performance, and is the primary way that the built-in XML middleware is authored.

PHP's [XPath implementation](http://php.net/manual/en/class.domxpath.php) only supports XPath 1.0, though, so keep this in mind.

## Decoupled Components

Before we knew what dependency injection was, SimplePie offered a wide set of injection points which leveraged [instantiating PHP classes by their string names](https://stackoverflow.com/questions/4578335/creating-php-class-instance-with-a-string). But despite our best efforts, there was still a lot of tight coupling happening.

SimplePie NG strongly adopts the dependency injection pattern and inversion of control, and encourages the use of PSR-11 containers for re-using complex sets of dependencies. All of this is balanced, however, with support for global settings such as your preferred logger.

## Middleware

At one point I thought that events was going to be the way to move forward, with things happening in the system and plugins could subscribe to those events. But with middleware coming out as the way to do stuff in HTTP post-[PSR-7](http://www.php-fig.org/psr/psr-7/), I realized that it was a better way to go. So that's how I'm building things — each specification that we want to support will come by way of middleware.

There is currently a [PSR-15](https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware) draft that talks about HTTP middleware (which this is not), but it borrows ideas from there and from Guzzle 6 middleware.

Keep an eye on the README for development status, but the plan is to do Atom 1.0 first, with other formats coming later.

## Standards

Since the advent of the PHP-FIG and the [PSR system](http://www.php-fig.org/psr/), things that were once left entirely to the developer have now been specified in documents with numbers on them. This enables more people to put "more wood behind fewer arrows", to use an idiom.

Where it makes sense, SimplePie NG will take advantage of off-the-shelf building blocks implementing various PSR specifications. This will allow components to become _pluggable_, and make it easier to use SimplePie in your modern PHP apps without duplicating functionality or imposing a steep learning curve.

## Doing Less

SimplePie focused so hard on being easy enough for mindless non-developers to use, that it had difficulty addressing the issues facing power-users.

It had built-in XML parsing, feed sanitization, HTTP support, caching support, favicon-fetching support, integration with posting content to a number of long-dead services, native Odeo support, and tried to do some clever things that ended up opening security holes. Some people would disable networking caching, causing SimplePie to pummel websites with requests, and would sometimes even knock them offline! I even had one person track down where I was working and threaten my life and the lives of the people I love because someone was misusing SimplePie, and he accused me personally!

SimplePie NG aims to do less. Far less. We're starting with parsing feeds into a data structure. These feeds are expected to be UTF-8. That's it.

We may provide some convenience methods for simplifying PSR-6/PSR-16 caching, PSR-7 HTTP requests, PSR-11 container interop, or tools for converting between character encodings, but those will come later. First, let's get parsing right.

## Coding Standards

PSR-1/2/12 are a solid foundation, but are not an entire coding style by themselves. I have taken the time to document all of the nitpicky patterns and nuances of my personal coding style. It goes well-beyond brace placement and tabs vs. spaces to cover topics such as docblock annotations, ternary operations and which variation of English to use. It aims for thoroughness and pedanticism over hoping that we can all get along.

<https://github.com/skyzyx/php-coding-standards>


.. reviewer-meta::
   :written-on: 2017-06-25
   :proofread-on: 2017-06-30
