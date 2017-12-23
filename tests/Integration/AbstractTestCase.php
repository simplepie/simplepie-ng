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

    public function setUp(): void
    {
        $this->feedDir  = __DIR__ . '/feeds';
        $this->goodAtom = \file_get_contents($this->feedDir . '/test.atom');

        $middleware = (new HandlerStack())
            ->append(new Atom(), 'atom');

        $this->simplepie = (new SimplePie())
            ->setMiddlewareStack($middleware);

        //------------------------------------------------------------------------------

        $stream = Psr7\stream_for($this->goodAtom);
        $parser = $this->simplepie->parseXml($stream, true);

        $this->feed = $parser->getFeed();
    }
}
