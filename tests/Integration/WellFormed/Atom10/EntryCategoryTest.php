<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Test\Integration\AbstractTestCase;

class EntryCategoryTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testLabel(): void
    {
        $stream   = $this->getFeed('/wellformed/atom10/entry_category_label.xml');
        $parser   = $this->simplepie->parseXml($stream);
        $feed     = $parser->getFeed();
        $entry    = $feed->getEntries()[0];
        $category = $entry->getCategories()[0];

        static::assertEquals('Atom 1.0 tests', (string) $category->getLabel());
    }

    public function testScheme(): void
    {
        $stream   = $this->getFeed('/wellformed/atom10/entry_category_scheme.xml');
        $parser   = $this->simplepie->parseXml($stream);
        $feed     = $parser->getFeed();
        $entry    = $feed->getEntries()[0];
        $category = $entry->getCategories()[0];

        static::assertEquals('http://feedparser.org/tests/', (string) $category->getScheme());
    }

    public function testTerm(): void
    {
        $stream   = $this->getFeed('/wellformed/atom10/entry_category_term.xml');
        $parser   = $this->simplepie->parseXml($stream);
        $feed     = $parser->getFeed();
        $entry    = $feed->getEntries()[0];
        $category = $entry->getCategories()[0];

        static::assertEquals('atom10', (string) $category->getTerm());
    }

    public function testTermNonAscii(): void
    {
        $stream   = $this->getFeed('/wellformed/atom10/entry_category_term_non_ascii.xml');
        $parser   = $this->simplepie->parseXml($stream);
        $feed     = $parser->getFeed();
        $entry    = $feed->getEntries()[0];
        $category = $entry->getCategories()[0];

        static::assertEquals('Freiräume', (string) $category->getTerm());
    }
}
