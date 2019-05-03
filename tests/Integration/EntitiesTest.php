<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration;

use IntlChar;
use SimplePie\Enum\Serialization;

class EntitiesTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->simplepie = $this->getSimplePie();
    }

    /**
     * Data Provider.
     */
    public function feeds(): iterable
    {
        $pattern = \sprintf('%s/entities/*.xml', $this->getFeedDir());
        $files   = \glob($pattern, \GLOB_MARK);

        foreach ($files as $file) {
            yield [\str_replace($this->getFeedDir(), '', $file)];
        }
    }

    /**
     * @dataProvider feeds
     */
    public function testEntities($feed): void
    {
        $stream = $this->getFeed($feed);
        $parser = $this->simplepie->parseXml($stream, true);
        $xq     = $parser->xpath()->query('/comment()')[0];
        $feed   = $parser->getFeed();

        \preg_match("/Expect:\\s+feed\\['title'\\] == '([^']*)'/", (string) $xq->textContent, $m);
        $title = $this->codepointCharacter($m[1]);

        static::assertEquals($title, (string) $feed->getTitle());
        static::assertEquals(Serialization::TEXT, $feed->getTitle()->getSerialization());
    }

    public function codepointCharacter(string $str): string
    {
        $str = \preg_replace_callback('/\\\\(u|x)([0-9a-f]{2,8})/i', static function ($m) {
            $hex = $m[2];
            $dec = \hexdec($hex);

            return IntlChar::chr($dec);
        }, $str);

        return $str;
    }
}
