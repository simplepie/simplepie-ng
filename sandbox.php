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

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));
$parser = $simplepie->parseXml($stream, true);

$feed = $parser->getFeed();

echo '--------------------------------------------------------------------------' . PHP_EOL;

echo 'feed->getId: ' . $feed->getId('atom10') . PHP_EOL;
echo 'feed->getLang: ' . $feed->getLang() . PHP_EOL;
echo 'feed->getLang->getSerialization: ' . $feed->getLang()->getSerialization() . PHP_EOL;
echo 'feed->getLanguage: ' . $feed->getLanguage() . PHP_EOL;
echo 'feed->getLanguage->getSerialization: ' . $feed->getLanguage()->getSerialization() . PHP_EOL;
echo 'feed->getRights: ' . $feed->getRights() . PHP_EOL;
echo 'feed->getSubtitle: ' . $feed->getSubtitle() . PHP_EOL;
echo 'feed->getSummary: ' . $feed->getSummary() . PHP_EOL;
echo 'feed->getTitle: ' . $feed->getTitle() . PHP_EOL;

echo '--------------------------------------------------------------------------' . PHP_EOL;

echo 'feed->getPublished: ' . $feed->getPublished()
    ->setTimezone(new \DateTimeZone('America/New_York'))
    ->format(DateFormat::RSS20) . PHP_EOL;

echo 'feed->getPubDate: ' . $feed->getPubDate()
    ->format(DateFormat::RSS20) . PHP_EOL;

echo 'feed->getUpdated: ' . $feed->getUpdated()
    ->setTimezone(new \DateTimeZone('America/Los_Angeles'))
    ->format(DateFormat::RSS20) . PHP_EOL;

echo '--------------------------------------------------------------------------' . PHP_EOL;

echo 'feed->getGenerator: ' . $feed->getGenerator() . PHP_EOL;
echo 'feed->getGenerator->getName: ' . $feed->getGenerator()->getName() . PHP_EOL;
echo 'feed->getGenerator->getUri: ' . $feed->getGenerator()->getUri() . PHP_EOL;
echo 'feed->getGenerator->getVersion: ' . $feed->getGenerator()->getVersion() . PHP_EOL;
echo 'feed->getGenerator->getVersion->getSerialization: ' . $feed->getGenerator()->getVersion()->getSerialization() . PHP_EOL;

echo '--------------------------------------------------------------------------' . PHP_EOL;
print_r($feed->getRoot());

echo PHP_EOL;
