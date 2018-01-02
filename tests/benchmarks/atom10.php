<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

require_once \dirname(__DIR__) . '/bootstrap.php';

$start = microtime(true);

use GuzzleHttp\Psr7;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use SimplePie\Enum\DateFormat;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;
use Skyzyx\UtilityPack\Bytes;

$__times__ = ($argv[1] ?? 1000);

echo sprintf('Memory: %s/%s', Bytes::format(memory_get_usage()), Bytes::format(memory_get_usage(true)));
echo ': Baseline memory usage.' . PHP_EOL;

$logger = new Logger('SimplePie');
$logger->pushHandler(new ErrorLogHandler(
    ErrorLogHandler::OPERATING_SYSTEM,
    LogLevel::DEBUG,
    true,
    false
));

$simplepie = (new SimplePie())
    ->setLogger($logger)
    ->setMiddlewareStack(
        (new HandlerStack())
            ->append(new Atom())
    )
;

echo sprintf('Memory: %s/%s', Bytes::format(memory_get_usage()), Bytes::format(memory_get_usage(true)));
echo ': Loading our logger and configuring middleware.' . PHP_EOL;

$xmlFile = \dirname(__DIR__) . '/Integration/feeds/full/atom10/tim-bray-500.xml';
echo sprintf('XML file size: %s', Bytes::format(filesize($xmlFile))) . PHP_EOL;

for ($i = 0; $i < ($argv[1] ?? $__times__); $i++) {
    echo '------------------------------------------------------------------------';
    echo PHP_EOL;

    $streamMemStart = memory_get_usage();
    $streamTimeStart = microtime(true);
    $stream = Psr7\stream_for(\file_get_contents($xmlFile));
    echo sprintf('Stream time: %s', microtime(true) - $streamTimeStart) . PHP_EOL;
    echo sprintf('Stream memory: %s', Bytes::format(memory_get_usage() - $streamMemStart)) . PHP_EOL;

    $treeMemStart = memory_get_usage();
    $treeTimeStart = microtime(true);
    $parser = $simplepie->parseXml($stream, true);
    echo sprintf('Parse tree time: %s', microtime(true) - $treeTimeStart) . PHP_EOL;
    echo sprintf('Parse tree memory: %s', Bytes::format(memory_get_usage() - $treeMemStart)) . PHP_EOL;

    $feed = $parser->getFeed();

    echo sprintf('Memory: %s/%s', Bytes::format(memory_get_usage()), Bytes::format(memory_get_usage(true)));
    echo ': Reading the XML file, parsing it into a data structure using middleware.' . PHP_EOL;

    unset($stream, $parser);

    echo sprintf('Memory: %s/%s', Bytes::format(memory_get_usage()), Bytes::format(memory_get_usage(true)));
    echo ': Unset $stream and $parser.' . PHP_EOL;

    $elseMemStart = memory_get_usage();
    $elseTimeStart = microtime(true);

    (string) $feed->getGenerator();
    (string) $feed->getGenerator()->getName();
    $feed->getGenerator()->getName()->getValue();
    $feed->getGenerator()->getName()->getSerialization();
    (string) $feed->getGenerator()->getVersion();
    $feed->getGenerator()->getVersion()->getValue();
    $feed->getGenerator()->getVersion()->getSerialization();
    (string) $feed->getGenerator()->getUrl();
    $feed->getGenerator()->getUrl()->getValue();
    $feed->getGenerator()->getUrl()->getSerialization();
    (string) $feed->getGenerator()->getUri();
    $feed->getGenerator()->getUri()->getValue();
    $feed->getGenerator()->getUri()->getSerialization();

    (string) $feed->getId();
    $feed->getId()->getValue();
    $feed->getId()->getSerialization();

    (string) $feed->getIcon();
    $feed->getIcon()->getUri()->getValue();
    $feed->getIcon()->getUri()->getSerialization();
    $feed->getIcon()->getUrl()->getValue();
    $feed->getIcon()->getUrl()->getSerialization();

    (string) $feed->getLanguage();
    $feed->getLanguage()->getValue();
    $feed->getLanguage()->getSerialization();

    (string) $feed->getLogo();
    $feed->getLogo()->getUri()->getValue();
    $feed->getLogo()->getUri()->getSerialization();
    $feed->getLogo()->getUrl()->getValue();
    $feed->getLogo()->getUrl()->getSerialization();

    $feed->getPubDate()
        ? $feed->getPubDate()->format(DateFormat::LONG_12HOUR)
        : $feed->getUpdated()
            ? $feed->getUpdated()->format(DateFormat::LONG_12HOUR)
            : null;

    (string) $feed->getRights();
    $feed->getRights()->getValue();
    $feed->getRights()->getSerialization();

    (string) $feed->getSubtitle();
    $feed->getSubtitle()->getValue();
    $feed->getSubtitle()->getSerialization();

    (string) $feed->getSummary();
    $feed->getSummary()->getValue();
    $feed->getSummary()->getSerialization();

    (string) $feed->getTitle();
    $feed->getTitle()->getValue();
    $feed->getTitle()->getSerialization();

    foreach ($feed->getAuthors() as $person) {
        (string) $person->getAvatar();
        $person->getAvatar()->getValue();
        $person->getAvatar()->getSerialization();
        (string) $person->getEmail();
        $person->getEmail()->getValue();
        $person->getEmail()->getSerialization();
        (string) $person->getName();
        $person->getName()->getValue();
        $person->getName()->getSerialization();
        (string) $person->getUri();
        $person->getUri()->getValue();
        $person->getUri()->getSerialization();
        (string) $person->getUrl();
        $person->getUrl()->getValue();
        $person->getUrl()->getSerialization();
    }
    unset($person);

    foreach ($feed->getContributors() as $person) {
        (string) $person->getAvatar();
        $person->getAvatar()->getValue();
        $person->getAvatar()->getSerialization();
        (string) $person->getEmail();
        $person->getEmail()->getValue();
        $person->getEmail()->getSerialization();
        (string) $person->getName();
        $person->getName()->getValue();
        $person->getName()->getSerialization();
        (string) $person->getUri();
        $person->getUri()->getValue();
        $person->getUri()->getSerialization();
        (string) $person->getUrl();
        $person->getUrl()->getValue();
        $person->getUrl()->getSerialization();
    }
    unset($person);

    foreach ($feed->getCategories() as $category) {
        (string) $category->getLabel();
        $category->getLabel()->getValue();
        $category->getLabel()->getSerialization();
        (string) $category->getScheme();
        $category->getScheme()->getValue();
        $category->getScheme()->getSerialization();
        (string) $category->getTerm();
        $category->getTerm()->getValue();
        $category->getTerm()->getSerialization();
    }
    unset($category);

    foreach ($feed->getLinks() as $link) {
        (string) $link->getHref();
        $link->getHref()->getValue();
        $link->getHref()->getSerialization();
        (string) $link->getHreflang();
        $link->getHreflang()->getValue();
        $link->getHreflang()->getSerialization();
        (string) $link->getLang();
        $link->getLang()->getValue();
        $link->getLang()->getSerialization();
        (string) $link->getLanguage();
        $link->getLanguage()->getValue();
        $link->getLanguage()->getSerialization();
        (string) $link->getLength();
        $link->getLength()->getValue();
        $link->getLength()->getSerialization();
        (string) $link->getMediaType();
        $link->getMediaType()->getValue();
        $link->getMediaType()->getSerialization();
        (string) $link->getRel();
        $link->getRel()->getValue();
        $link->getRel()->getSerialization();
        (string) $link->getRelationship();
        $link->getRelationship()->getValue();
        $link->getRelationship()->getSerialization();
        (string) $link->getTitle();
        $link->getTitle()->getValue();
        $link->getTitle()->getSerialization();
        (string) $link->getType();
        $link->getType()->getValue();
        $link->getType()->getSerialization();
        (string) $link->getUri();
        $link->getUri()->getValue();
        $link->getUri()->getSerialization();
        (string) $link->getUrl();
        $link->getUrl()->getValue();
        $link->getUrl()->getSerialization();
    }
    unset($link);

    foreach ($feed->getLinks($feed->getDefaultNs(), 'self') as $link) {
        (string) $link->getHref();
        $link->getHref()->getValue();
        $link->getHref()->getSerialization();
        (string) $link->getHreflang();
        $link->getHreflang()->getValue();
        $link->getHreflang()->getSerialization();
        (string) $link->getLang();
        $link->getLang()->getValue();
        $link->getLang()->getSerialization();
        (string) $link->getLanguage();
        $link->getLanguage()->getValue();
        $link->getLanguage()->getSerialization();
        (string) $link->getLength();
        $link->getLength()->getValue();
        $link->getLength()->getSerialization();
        (string) $link->getMediaType();
        $link->getMediaType()->getValue();
        $link->getMediaType()->getSerialization();
        (string) $link->getRel();
        $link->getRel()->getValue();
        $link->getRel()->getSerialization();
        (string) $link->getRelationship();
        $link->getRelationship()->getValue();
        $link->getRelationship()->getSerialization();
        (string) $link->getTitle();
        $link->getTitle()->getValue();
        $link->getTitle()->getSerialization();
        (string) $link->getType();
        $link->getType()->getValue();
        $link->getType()->getSerialization();
        (string) $link->getUri();
        $link->getUri()->getValue();
        $link->getUri()->getSerialization();
        (string) $link->getUrl();
        $link->getUrl()->getValue();
        $link->getUrl()->getSerialization();
    }
    unset($link);

    foreach ($feed->getItems() as $entry) {
        (string) $entry->getId();
        $entry->getId()->getValue();
        $entry->getId()->getSerialization();

        (string) $entry->getLanguage();
        $entry->getLanguage()->getValue();
        $entry->getLanguage()->getSerialization();

        $entry->getPubDate()
            ? $entry->getPubDate()->format(DateFormat::LONG_12HOUR)
            : $entry->getUpdated()
                ? $entry->getUpdated()->format(DateFormat::LONG_12HOUR)
                : null;

        (string) $entry->getRights();
        $entry->getRights()->getValue();
        $entry->getRights()->getSerialization();

        (string) $entry->getSummary();
        $entry->getSummary()->getValue();
        $entry->getSummary()->getSerialization();

        (string) $entry->getTitle();
        $entry->getTitle()->getValue();
        $entry->getTitle()->getSerialization();

        (string) $entry->getContent();
        $entry->getContent()->getValue();
        $entry->getContent()->getSerialization();

        foreach ($entry->getAuthors() as $person) {
            (string) $person->getAvatar();
            $person->getAvatar()->getValue();
            $person->getAvatar()->getSerialization();
            (string) $person->getEmail();
            $person->getEmail()->getValue();
            $person->getEmail()->getSerialization();
            (string) $person->getName();
            $person->getName()->getValue();
            $person->getName()->getSerialization();
            (string) $person->getUri();
            $person->getUri()->getValue();
            $person->getUri()->getSerialization();
            (string) $person->getUrl();
            $person->getUrl()->getValue();
            $person->getUrl()->getSerialization();
        }
        unset($person);

        foreach ($entry->getContributors() as $person) {
            (string) $person->getAvatar();
            $person->getAvatar()->getValue();
            $person->getAvatar()->getSerialization();
            (string) $person->getEmail();
            $person->getEmail()->getValue();
            $person->getEmail()->getSerialization();
            (string) $person->getName();
            $person->getName()->getValue();
            $person->getName()->getSerialization();
            (string) $person->getUri();
            $person->getUri()->getValue();
            $person->getUri()->getSerialization();
            (string) $person->getUrl();
            $person->getUrl()->getValue();
            $person->getUrl()->getSerialization();
        }
        unset($person);

        foreach ($entry->getCategories() as $category) {
            (string) $category->getLabel();
            $category->getLabel()->getValue();
            $category->getLabel()->getSerialization();
            (string) $category->getScheme();
            $category->getScheme()->getValue();
            $category->getScheme()->getSerialization();
            (string) $category->getTerm();
            $category->getTerm()->getValue();
            $category->getTerm()->getSerialization();
        }
        unset($category);

        foreach ($entry->getLinks() as $link) {
            (string) $link->getHref();
            $link->getHref()->getValue();
            $link->getHref()->getSerialization();
            (string) $link->getHreflang();
            $link->getHreflang()->getValue();
            $link->getHreflang()->getSerialization();
            (string) $link->getLang();
            $link->getLang()->getValue();
            $link->getLang()->getSerialization();
            (string) $link->getLanguage();
            $link->getLanguage()->getValue();
            $link->getLanguage()->getSerialization();
            (string) $link->getLength();
            $link->getLength()->getValue();
            $link->getLength()->getSerialization();
            (string) $link->getMediaType();
            $link->getMediaType()->getValue();
            $link->getMediaType()->getSerialization();
            (string) $link->getRel();
            $link->getRel()->getValue();
            $link->getRel()->getSerialization();
            (string) $link->getRelationship();
            $link->getRelationship()->getValue();
            $link->getRelationship()->getSerialization();
            (string) $link->getTitle();
            $link->getTitle()->getValue();
            $link->getTitle()->getSerialization();
            (string) $link->getType();
            $link->getType()->getValue();
            $link->getType()->getSerialization();
            (string) $link->getUri();
            $link->getUri()->getValue();
            $link->getUri()->getSerialization();
            (string) $link->getUrl();
            $link->getUrl()->getValue();
            $link->getUrl()->getSerialization();
        }
        unset($link);

        foreach ($entry->getLinks($feed->getDefaultNs(), 'self') as $link) {
            (string) $link->getHref();
            $link->getHref()->getValue();
            $link->getHref()->getSerialization();
            (string) $link->getHreflang();
            $link->getHreflang()->getValue();
            $link->getHreflang()->getSerialization();
            (string) $link->getLang();
            $link->getLang()->getValue();
            $link->getLang()->getSerialization();
            (string) $link->getLanguage();
            $link->getLanguage()->getValue();
            $link->getLanguage()->getSerialization();
            (string) $link->getLength();
            $link->getLength()->getValue();
            $link->getLength()->getSerialization();
            (string) $link->getMediaType();
            $link->getMediaType()->getValue();
            $link->getMediaType()->getSerialization();
            (string) $link->getRel();
            $link->getRel()->getValue();
            $link->getRel()->getSerialization();
            (string) $link->getRelationship();
            $link->getRelationship()->getValue();
            $link->getRelationship()->getSerialization();
            (string) $link->getTitle();
            $link->getTitle()->getValue();
            $link->getTitle()->getSerialization();
            (string) $link->getType();
            $link->getType()->getValue();
            $link->getType()->getSerialization();
            (string) $link->getUri();
            $link->getUri()->getValue();
            $link->getUri()->getSerialization();
            (string) $link->getUrl();
            $link->getUrl()->getValue();
            $link->getUrl()->getSerialization();
        }
        unset($link);
    }

    echo sprintf('Everything else time: %s', microtime(true) - $elseTimeStart) . PHP_EOL;
    $elseMem = memory_get_usage() - $elseMemStart;
    echo sprintf('Everything else memory: %s%s', ($elseMem >= 0 ? '' : '-'), Bytes::format(abs($elseMem))) . PHP_EOL;

    echo sprintf('Memory: %s/%s', Bytes::format(memory_get_usage()), Bytes::format(memory_get_usage(true)));
    echo ': ' . $i . PHP_EOL;
}

echo '------------------------------------------------------------------------';
echo PHP_EOL . PHP_EOL;

$time = microtime(true) - $start;
echo 'Total time: ' . $time . ' seconds.' . PHP_EOL;
echo 'Each run (avg): ' . $time / $__times__ . ' seconds.' . PHP_EOL;
echo sprintf('Peak memory: %s/%s', Bytes::format(memory_get_peak_usage()), Bytes::format(memory_get_peak_usage(true))) . PHP_EOL;
echo 'Feed entries: ' . count($feed->getEntries()) . PHP_EOL;
echo PHP_EOL;

opcache_reset();
