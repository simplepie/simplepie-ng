<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Enum;

use SimplePie\Enum\XmlNs;
use SimplePie\Test\Unit\AbstractTestCase;

class XmlNsTest extends AbstractTestCase
{
    public function testIntrospect(): void
    {
        $this->assertSame(XmlNs::introspect(), [
            'ATOM_03' => 'http://purl.org/atom/ns#',
            'ATOM_10' => 'http://www.w3.org/2005/Atom',
            'RDF'     => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
            'RSS_090' => 'http://my.netscape.com/rdf/simple/0.9/',
            'RSS_10'  => 'http://purl.org/rss/1.0/',
            'RSS_20'  => '',
            'XHTML'   => 'http://www.w3.org/1999/xhtml',
            'XML'     => 'http://www.w3.org/XML/1998/namespace',
        ]);
    }

    public function testIntrospectKeys(): void
    {
        $this->assertSame(XmlNs::introspectKeys(), [
            'ATOM_03',
            'ATOM_10',
            'RDF',
            'RSS_090',
            'RSS_10',
            'RSS_20',
            'XHTML',
            'XML',
        ]);
    }

    public function testHasValue(): void
    {
        $this->assertTrue(XmlNs::hasValue(XmlNs::ATOM_03));
        $this->assertTrue(XmlNs::hasValue(XmlNs::ATOM_10));
        $this->assertTrue(XmlNs::hasValue(XmlNs::RDF));
        $this->assertTrue(XmlNs::hasValue(XmlNs::RSS_090));
        $this->assertTrue(XmlNs::hasValue(XmlNs::RSS_10));
        $this->assertTrue(XmlNs::hasValue(XmlNs::RSS_20));
        $this->assertTrue(XmlNs::hasValue(XmlNs::XHTML));
        $this->assertTrue(XmlNs::hasValue(XmlNs::XML));

        $this->assertFalse(XmlNs::hasValue('nope'));
    }
}
