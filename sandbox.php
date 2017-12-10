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

echo 'count(contributors): ' . count($feed->getContributors()) . PHP_EOL;
$count = 1;

foreach ($feed->getContributors() as $contributor) {
    echo sprintf('Contributor #%s:', $count);
    echo 'contributor: ' . (string) $contributor . PHP_EOL;
    echo 'contributor->getName: ' . (string) $contributor->getName() . PHP_EOL;
    echo 'contributor->getName->getSerialization: ' . (string) $contributor->getName()->getSerialization() . PHP_EOL;
    echo 'contributor->getUrl: ' . (string) $contributor->getUrl() . PHP_EOL;
    echo 'contributor->getUrl->getSerialization: ' . (string) $contributor->getUrl()->getSerialization() . PHP_EOL;
    echo 'contributor->getEmail: ' . (string) $contributor->getEmail() . PHP_EOL;
    echo 'contributor->getEmail->getSerialization: ' . (string) $contributor->getEmail()->getSerialization() . PHP_EOL;
    echo 'contributor->getAvatar: ' . (string) $contributor->getAvatar() . PHP_EOL;
    echo 'contributor->getAvatar->getSerialization: ' . (string) $contributor->getAvatar()->getSerialization() . PHP_EOL;
    echo PHP_EOL;
}

echo '--------------------------------------------------------------------------' . PHP_EOL;

// print_r($feed->getRoot());
echo PHP_EOL;
