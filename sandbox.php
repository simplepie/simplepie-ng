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

echo 'feed->getRights: ' . $feed->getRights() . PHP_EOL;

echo '--------------------------------------------------------------------------' . PHP_EOL;

// print_r($feed->getRoot());
echo PHP_EOL;
