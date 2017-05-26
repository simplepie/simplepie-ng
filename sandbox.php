#! /usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Pimple\Container;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use SimplePie\Provider\QuickProvider;
use SimplePie\SimplePie;

$container = new ServiceContainer();
$container->addConfig(new QuickProvider());
$container['testing'] = 'Testing!';

$simplepie = new SimplePie($container);

$c = $simplepie->getContainer();
echo $c['testing'] . PHP_EOL;
