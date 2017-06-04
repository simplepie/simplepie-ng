#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\FeedType;
use SimplePie\Provider\QuickProvider;
use SimplePie\SimplePie;
use Skyzyx\UtilityPack\Types;

$container = new ServiceContainer();
$container->addConfig(new QuickProvider());
$simplepie = new SimplePie($container);
$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));

$parser = $simplepie->parseXml($stream, true);

$feed = $parser->getFeed();

print_r($feed->getRoot());
