<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use SimplePie\SimplePie;

class SimplePieTest extends AbstractTestCase
{
    public function testLibxml(): void
    {
        $simplepie = (new SimplePie())
            ->setLibxml(
                \LIBXML_HTML_NOIMPLIED
                | \LIBXML_BIGLINES
                | \LIBXML_COMPACT
                | \LIBXML_HTML_NODEFDTD
                | \LIBXML_NOBLANKS
                | \LIBXML_NOENT
                | \LIBXML_NOXMLDECL
                | \LIBXML_NSCLEAN
                | \LIBXML_PARSEHUGE
            );

        $this->assertEquals(4792582, $simplepie->getLibxml());
    }
}
