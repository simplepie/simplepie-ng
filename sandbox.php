#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SimplePie\Configuration;
use SimplePie\Container;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;

//------------------------------------------------------------------------------

$container = new Container();

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

$container['simplepie.middleware'] = function () {
    return $stack = (new HandlerStack())
        ->append(new Atom(), 'atom')
    ;
};

Configuration::setContainer($container);

//------------------------------------------------------------------------------

$simplepie = new SimplePie();

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));
$parser = $simplepie->parseXml($stream, true);

$feed = $parser->getFeed();
print_r($feed->getRoot());

echo PHP_EOL;
