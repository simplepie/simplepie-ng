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

class EntryContentTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    public function testMathMl(): void
    {
        $stream = $this->getFeed('/wellformed/lang/entry_content_xml_lang_inherit.xml');
        $parser = $this->simplepie->parseXml($stream);
        $feed   = $parser->getFeed();

        $this->assertEquals('en', (string) $feed->getLang());
        $this->assertEquals(Serialization::TEXT, $feed->getLang()->getSerialization());
    }
}
