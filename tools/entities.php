#! /usr/bin/env php
<?php
declare(strict_types=1);

require_once \dirname(__DIR__) . '/vendor/autoload.php';

// Twig bootstrapping
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig   = new Twig_Environment($loader, [
    'debug'            => true,
    'charset'          => 'utf-8',
    'cache'            => '/tmp',
    'auto_reload'      => true,
    'strict_variables' => true,
    'optimizations'    => -1,
]);
$twig->addExtension(new Twig_Extension_Debug());

$template = $twig->load('entities.dtd.twig');

//-------------------------------------------------------------------------------

$entities    = \json_decode(\file_get_contents(\dirname(__DIR__) . '/resources/entities.json'));
$enumerables = [];

foreach ($entities as $entity => $codepoints) {
    $enumerables[] = (object) [
        'amp'       => \str_replace(['&', ';'], '', $entity),
        'codepoint' => (static function () use ($codepoints) {
            return \implode('', \array_map(static function ($p) {
                return \sprintf(
                    '&#x%s;',
                    \mb_strtoupper(\dechex($p))
                );
            }, $codepoints->codepoints));
        })(),
    ];
}

//-------------------------------------------------------------------------------

$output = $template->render([
    'entities' => $enumerables,
]);

$writePath = \sprintf(
    '%s/entities.dtd',
    \dirname(__DIR__) . '/src'
);

\file_put_contents($writePath, $output);
