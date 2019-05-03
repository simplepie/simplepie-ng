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

class EntryAuthorTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testEmail(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_email.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('me@example.com', (string) $author->getEmail());
    }

    public function testEmails(): void
    {
        $stream  = $this->getFeed('/wellformed/atom10/entry_authors_email.xml');
        $parser  = $this->simplepie->parseXml($stream);
        $feed    = $parser->getFeed();
        $entry   = $feed->getEntries()[0];
        $authors = $entry->getAuthors();

        $this->assertEquals('one@one.com', (string) $authors[0]->getEmail());
        $this->assertEquals('two@two.com', (string) $authors[1]->getEmail());
    }

    public function testStringFormat(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_map_author.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('Example author <http://example.com/>', (string) $author);
    }

    public function testStringFormat2(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_map_author_2.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('Example author <http://example.com/>', (string) $author);
    }

    public function testName(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_name.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('Example author', (string) $author->getName());
    }

    public function testNames(): void
    {
        $stream  = $this->getFeed('/wellformed/atom10/entry_authors_name.xml');
        $parser  = $this->simplepie->parseXml($stream);
        $feed    = $parser->getFeed();
        $entry   = $feed->getEntries()[0];
        $authors = $entry->getAuthors();

        $this->assertEquals('one', (string) $authors[0]->getName());
        $this->assertEquals('two', (string) $authors[1]->getName());
    }

    public function testUri(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_uri.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('http://example.com/', (string) $author->getUri());
    }

    public function testUris(): void
    {
        $stream  = $this->getFeed('/wellformed/atom10/entry_authors_uri.xml');
        $parser  = $this->simplepie->parseXml($stream);
        $feed    = $parser->getFeed();
        $entry   = $feed->getEntries()[0];
        $authors = $entry->getAuthors();

        $this->assertEquals('http://one.com/', (string) $authors[0]->getUri());
        $this->assertEquals('http://two.com/', (string) $authors[1]->getUri());
    }

    public function testUrl(): void
    {
        $stream = $this->getFeed('/wellformed/atom10/entry_author_url.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];
        $author = $entry->getAuthors()[0];

        $this->assertEquals('http://example.com/', (string) $author->getUrl());
    }

    public function testUrls(): void
    {
        $stream  = $this->getFeed('/wellformed/atom10/entry_authors_url.xml');
        $parser  = $this->simplepie->parseXml($stream);
        $feed    = $parser->getFeed();
        $entry   = $feed->getEntries()[0];
        $authors = $entry->getAuthors();

        $this->assertEquals('http://one.com/', (string) $authors[0]->getUrl());
        $this->assertEquals('http://two.com/', (string) $authors[1]->getUrl());
    }
}
