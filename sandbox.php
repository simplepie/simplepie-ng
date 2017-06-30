#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\FeedType;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;
use Skyzyx\UtilityPack\Types;

//------------------------------------------------------------------------------

$logger = new Logger('SimplePie');
$logger->pushHandler(new ErrorLogHandler(
    ErrorLogHandler::OPERATING_SYSTEM,
    LogLevel::DEBUG,
    true,
    false
));

$stack = (new HandlerStack($logger))
    ->append(new Atom($logger), 'atom')
;

//------------------------------------------------------------------------------

$simplepie = new SimplePie([
    'logger'     => $logger,
    'middleware' => $stack,
]);

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));
$parser = $simplepie->parseXml($stream, true);

$feed = $parser->getFeed();
print_r([
    'value'   => $feed->getSummary()->getValue(),
    'serialz' => $feed->getSummary()->getSerialization()
]);
// print_r($feed->getRoot());

echo PHP_EOL;
