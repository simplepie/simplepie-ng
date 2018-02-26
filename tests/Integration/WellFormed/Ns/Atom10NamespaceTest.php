<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\WellFormed\Ns;

use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;

class Atom10NamespaceTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testMathMl(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atommathml.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $mathml = '<math xmlns="http://www.w3.org/1998/Math/MathML">' .
            '<mrow xlink:type="simple" xlink:show="replace" xlink:href="http://golem.ph.utexas.edu">' .
                '<mrow>' .
                    '<mi>a</mi>' .
                    '<mo>+</mo>' .
                    '<mi>b</mi>' .
                '</mrow>' .
            '</mrow>' .
        '</math>';

        $this->assertEquals(
            \trim($mathml),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }

    public function testSvg(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atomsvg.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $svg = '<svg:svg version="1.1" baseProfile="full" width="300px" height="200px">' .
            '<svg:circle cx="150px" cy="100px" r="50px" fill="#ff0000" stroke="#000000" stroke-width="5px"/>' .
        '</svg:svg>';

        $this->assertEquals(
            \trim($svg),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }

    public function testSvgDesc(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atomsvgdesc.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" ' .
            'version="1.1" baseProfile="full" width="300px" height="200px"' .
        '>' .
            '<desc>' .
                '<abbr>foo</abbr>' .
            '</desc>' .
            '<circle cx="150px" cy="100px" r="50px" fill="#ff0000" stroke="#000000" stroke-width="5px"/>' .
        '</svg>';

        $this->assertEquals(
            \trim($svg),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }

    public function testSvgTitle(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atomsvgtitle.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" ' .
            'version="1.1" baseProfile="full" width="300px" height="200px"' .
        '>' .
            '<title>foo</title>' .
            '<circle cx="150px" cy="100px" r="50px" fill="#ff0000" stroke="#000000" stroke-width="5px"/>' .
        '</svg>';

        $this->assertEquals(
            \trim($svg),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }

    public function testXlink(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atomxlink.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $svg = '<svg:svg version="1.1" baseProfile="full" width="300px" height="200px">' .
            '<svg:a xlink:href="http://example.com/">' .
                '<svg:circle cx="150px" cy="100px" r="50px" fill="#ff0000" stroke="#000000" stroke-width="5px"/>' .
            '</svg:a>' .
        '</svg:svg>';

        $this->assertEquals(
            \trim($svg),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }

    public function testSvgDcTitle(): void
    {
        $stream = $this->getFeed('/wellformed/namespace/atomsvgdctitle.xml');
        $parser = $this->simplepie->parseXmlFromStream($stream, true);
        $feed   = $parser->getFeed();
        $entry  = $feed->getEntries()[0];

        $svg = '<p>Before</p>' .
        '<svg xmlns="http://www.w3.org/2000/svg">' .
            '<metadata>' .
                '<rdf:RDF ' .
                    'xmlns:cc="http://web.resource.org/cc/" ' .
                    'xmlns:dc="http://purl.org/dc/elements/1.1/" ' .
                    'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"' .
                '>' .
                    '<cc:Work rdf:about="">' .
                        '<dc:title>Christmas Tree</dc:title>' .
                        '<dc:description/>' .
                        '<dc:creator>' .
                            '<cc:Agent>' .
                                '<dc:title>Aaron Spike</dc:title>' .
                            '</cc:Agent>' .
                        '</dc:creator>' .
                    '</cc:Work>' .
                    '<cc:License rdf:about="http://web.resource.org/cc/PublicDomain">' .
                        '<cc:permits rdf:resource="http://web.resource.org/cc/Reproduction"/>' .
                        '<cc:permits rdf:resource="http://web.resource.org/cc/Distribution"/>' .
                        '<cc:permits rdf:resource="http://web.resource.org/cc/DerivativeWorks"/>' .
                    '</cc:License>' .
                '</rdf:RDF>' .
            '</metadata>' .
        '</svg>' .
        '<p>After</p>';

        $this->assertEquals(
            \trim($svg),
            (string) $entry->getContent()
        );
        $this->assertEquals(Serialization::XHTML, $entry->getContent()->getSerialization());
    }
}
