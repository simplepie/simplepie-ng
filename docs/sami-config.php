<?php
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$dir = dirname(__DIR__) . '/src';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir)
;

// generate documentation for all v2.0.* tags, the 2.0 branch, and the master one
$versions = GitVersionCollection::create($dir)
    ->add('master', 'master branch')
;

return new Sami($iterator, array(
    'theme'                => 'symfony',
    'versions'             => $versions,
    'title'                => 'SimplePie NG API',
    'build_dir'            => __DIR__ . '/_build/api/%version%',
    'cache_dir'            => __DIR__ . '/_cache/api/%version%',
    'remote_repository'    => new GitHubRemoteRepository('simplepie/simplepie-ng', dirname($dir)),
    'default_opened_level' => 2,
));
