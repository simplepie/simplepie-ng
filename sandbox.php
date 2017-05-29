#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Pimple\Container;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use SimplePie\Configure;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\Mime;
use SimplePie\Provider\QuickProvider;
use SimplePie\SimplePie;
use Skyzyx\UtilityPack\Types;
use SimplePie\Dictionary\Ns;

$container = new ServiceContainer();
$container->addConfig(new QuickProvider());
$configuration = new Configure($container);

$simplepie = new SimplePie($configuration);

// $client = new Client([
//     'timeout'  => 2.0,
// ]);

// $response = $client->get('https://github.com/skyzyx/signer/releases.atom');

$stream = Psr7\stream_for(file_get_contents(__DIR__ . '/releases.atom'));
$dom = $simplepie->parseXml($stream)->getDomDocument();

$xpath = new DOMXPath($dom);
$namespace = new Ns($dom);

if (
    !is_null(
        $ns = $namespace->getPreferredNamespaceAlias(
            $dom->documentElement->namespaceURI
        )
    )
) {
    $xpath->registerNamespace($ns, $dom->documentElement->namespaceURI);
}

foreach ($xpath->query('/atom10:feed/atom10:entry') as $node) {
    foreach ($xpath->query('atom10:title', $node) as $node2) {
        echo $node2->nodeValue . PHP_EOL;
    }
}
