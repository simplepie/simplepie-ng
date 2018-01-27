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

$twig->addFunction(new Twig_Function('timestamp', static function () {
    return \str_replace('+00:00', 'Z', \gmdate(DATE_ATOM));
}));

//-------------------------------------------------------------------------------

$entities    = \json_decode(\file_get_contents(\dirname(__DIR__) . '/resources/entities.json'));
$enumerables = [];

// Figure out what they key => value should be
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
        'codepoint_x' => (static function () use ($codepoints) {
            return \implode('', \array_map(static function ($p) {
                return \sprintf(
                    '\x%s',
                    \mb_strtoupper(\dechex($p))
                );
            }, $codepoints->codepoints));
        })(),
        'codepoint_u' => (static function () use ($codepoints) {
            return \implode('', \array_map(static function ($p) {
                return \sprintf('\u%s', $p);
            }, $codepoints->codepoints));
        })(),
        'char' => (static function () use ($codepoints) {
            return \implode('', \array_map(static function ($p) {
                return \addcslashes(IntlChar::chr($p), "\n`\\\"");
            }, $codepoints->codepoints));
        })(),
    ];
}

// Sort alphabetically, naturally
\usort($enumerables, static function (stdClass $a, stdClass $b) {
    return \strcasecmp($a->amp, $b->amp);
});

// Make sure that the `=>` signs align in the generated output
$longestKey = \mb_strlen(
    \array_reduce($enumerables, static function (stdClass $a, stdClass $b) {
        return (\mb_strlen($a ?? '') > \mb_strlen($b->amp))
            ? $a
            : $b->amp;
    })
);

foreach ($enumerables as &$enum) {
    $enum->padded_amp = \str_pad("'" . $enum->amp . "'", $longestKey + 2, ' ', STR_PAD_RIGHT);
}

//-------------------------------------------------------------------------------
// entities.dtd

$template = $twig->load('entities.dtd.twig');
$output   = $template->render([
    'entities' => $enumerables,
]);

$writePath = \sprintf(
    '%s/entities.dtd',
    \dirname(__DIR__) . '/resources'
);

\file_put_contents($writePath, $output);

//-------------------------------------------------------------------------------
// Entity.php

$template = $twig->load('Entity.php.twig');
$output   = $template->render([
    'entities' => $enumerables,
]);

$writePath = \sprintf(
    '%s/Entity.php',
    \dirname(__DIR__) . '/src/Dictionary'
);

\file_put_contents($writePath, $output);
