#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Pimple\Container;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\Mime;
use SimplePie\Provider\QuickProvider;
use SimplePie\SimplePie;

$container = new ServiceContainer();
$container->addConfig(new QuickProvider());
$container['testing'] = 'Testing!';

$simplepie = new SimplePie($container);

// $client = new Client([
//     'timeout'  => 2.0,
// ]);

// $response = $client->get('https://github.com/skyzyx/signer/releases.atom');

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));

print_r($simplepie->parsePsr7Stream($stream, Mime::APPLICATION_ATOM_XML));
