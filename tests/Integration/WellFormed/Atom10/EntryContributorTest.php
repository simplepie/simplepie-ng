<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Atom;

use SimplePie\Test\Integration\AbstractTestCase;

class EntryContributorTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testEmail(): void
    {
        $stream      = $this->getFeed('/wellformed/atom10/entry_contributor_email.xml');
        $parser      = $this->simplepie->parseXml($stream);
        $feed        = $parser->getFeed();
        $entry       = $feed->getEntries()[0];
        $contributor = $entry->getContributors()[0];

        $this->assertEquals('me@example.com', (string) $contributor->getEmail());
    }

    public function testEmails(): void
    {
        $stream       = $this->getFeed('/wellformed/atom10/entry_contributor_multiple.xml');
        $parser       = $this->simplepie->parseXml($stream);
        $feed         = $parser->getFeed();
        $entry        = $feed->getEntries()[0];
        $contributors = $entry->getContributors();

        $this->assertEquals('me@example.com', (string) $contributors[0]->getEmail());
        $this->assertEquals('you@example.com', (string) $contributors[1]->getEmail());
    }

    public function testName(): void
    {
        $stream      = $this->getFeed('/wellformed/atom10/entry_contributor_name.xml');
        $parser      = $this->simplepie->parseXml($stream);
        $feed        = $parser->getFeed();
        $entry       = $feed->getEntries()[0];
        $contributor = $entry->getContributors()[0];

        $this->assertEquals('Example contributor', (string) $contributor->getName());
    }

    public function testNames(): void
    {
        $stream       = $this->getFeed('/wellformed/atom10/entry_contributor_multiple.xml');
        $parser       = $this->simplepie->parseXml($stream);
        $feed         = $parser->getFeed();
        $entry        = $feed->getEntries()[0];
        $contributors = $entry->getContributors();

        $this->assertEquals('Contributor 1', (string) $contributors[0]->getName());
        $this->assertEquals('Contributor 2', (string) $contributors[1]->getName());
    }

    public function testUri(): void
    {
        $stream      = $this->getFeed('/wellformed/atom10/entry_contributor_uri.xml');
        $parser      = $this->simplepie->parseXml($stream);
        $feed        = $parser->getFeed();
        $entry       = $feed->getEntries()[0];
        $contributor = $entry->getContributors()[0];

        $this->assertEquals('http://example.com/', (string) $contributor->getUri());
    }

    public function testUris(): void
    {
        $stream       = $this->getFeed('/wellformed/atom10/entry_contributor_multiple.xml');
        $parser       = $this->simplepie->parseXml($stream);
        $feed         = $parser->getFeed();
        $entry        = $feed->getEntries()[0];
        $contributors = $entry->getContributors();

        $this->assertEquals('http://example.com/', (string) $contributors[0]->getUri());
        $this->assertEquals('http://two.example.com/', (string) $contributors[1]->getUri());
    }

    public function testUrl(): void
    {
        $stream      = $this->getFeed('/wellformed/atom10/entry_contributor_url.xml');
        $parser      = $this->simplepie->parseXml($stream);
        $feed        = $parser->getFeed();
        $entry       = $feed->getEntries()[0];
        $contributor = $entry->getContributors()[0];

        $this->assertEquals('http://example.com/', (string) $contributor->getUrl());
    }

    public function testUrls(): void
    {
        $stream       = $this->getFeed('/wellformed/atom10/entry_contributor_multiple.xml');
        $parser       = $this->simplepie->parseXml($stream);
        $feed         = $parser->getFeed();
        $entry        = $feed->getEntries()[0];
        $contributors = $entry->getContributors();

        $this->assertEquals('http://example.com/', (string) $contributors[0]->getUrl());
        $this->assertEquals('http://two.example.com/', (string) $contributors[1]->getUrl());
    }
}
