<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration;

use GuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;

abstract class AbstractTestCase extends TestCase
{
    public $feedDir;

    public $goodAtom;

    public $simplepie;

    public $feed;

    public function getFeedDir(): string
    {
        $this->feedDir  = __DIR__ . '/feeds';

        return $this->feedDir;
    }

    public function getFeed(string $path)
    {
        return Psr7\stream_for(
            file_get_contents($this->getFeedDir() . $path)
        );
    }

    public function getSimplePie(): SimplePie
    {
        return (new SimplePie())
            ->setMiddlewareStack(
                (new HandlerStack())
                    ->append(new Atom())
            );
    }

    public function setUp(): void
    {
        $this->goodAtom = \file_get_contents($this->getFeedDir() . '/full/atom10/test.atom');
        $this->simplepie = $this->getSimplePie();

        $stream = Psr7\stream_for($this->goodAtom);
        $parser = $this->simplepie->parseXml($stream, true);

        $this->feed = $parser->getFeed();
    }
}
