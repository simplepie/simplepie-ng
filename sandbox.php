#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SimplePie\Configuration;
use SimplePie\Container;
use SimplePie\Enum\DateFormat;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;

//------------------------------------------------------------------------------

$logger = new Logger('SimplePie');
$logger->pushHandler(new ErrorLogHandler(
    ErrorLogHandler::OPERATING_SYSTEM,
    LogLevel::DEBUG,
    true,
    false
));

$middleware = (new HandlerStack())
    ->append(new Atom(), 'atom')
;

$simplepie = (new SimplePie())
    ->setLogger($logger)
    ->setMiddlewareStack($middleware)
;

//------------------------------------------------------------------------------

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/tests/Integration/feeds/test.atom'));
$parser = $simplepie->parseXml($stream, true);

$feed = $parser->getFeed();

echo '--------------------------------------------------------------------------' . PHP_EOL;

echo 'count(feed->getLinks): ' . count($feed->getLinks()) . PHP_EOL;
echo PHP_EOL;

$count = 1;
foreach ($feed->getLinks() as $link) {
    echo sprintf('Link #%d', $count) . PHP_EOL;
    echo 'link->getUrl: ' . $link->getUrl() . PHP_EOL;
    echo 'link->getRelationship: ' . $link->getRelationship() . PHP_EOL;
    echo 'link->getMediaType: ' . $link->getMediaType() . PHP_EOL;
    echo 'link->getLanguage: ' . $link->getLanguage() . PHP_EOL;
    echo 'link->getTitle: ' . $link->getTitle() . PHP_EOL;
    echo 'link->getLength: ' . $link->getLength() . PHP_EOL;

    echo PHP_EOL;
    $count++;
}

print_r(
    array_values(
        array_filter($feed->getLinks(), function ($l): bool {
            return 'self' === $l->getRelationship()->getValue();
        })
    )
);

echo '--------------------------------------------------------------------------' . PHP_EOL;

// print_r($feed->getRoot());
echo PHP_EOL;
