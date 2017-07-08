<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use SimplePie\Enum\FeedType;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;

class HandlerStackTest extends AbstractTestCase
{
    // public function testBasicAppend()
    // {
    //     $stack = (new HandlerStack())
    //         ->append(new Atom());
    // }

    public function testAppendClosure()
    {
        $stack = (new HandlerStack())
            ->append(new Atom())
            ->appendClosure(FeedType::ALL, function () {
            })
            ->appendClosure(FeedType::JSON, function () {
            })
            ->appendClosure(FeedType::HTML, function () {
            })
            ->appendClosure(FeedType::XML, function () {
            });

        $this->assertEquals(2, \count($stack->debugStack()['json']));
        $this->assertEquals(2, \count($stack->debugStack()['html']));
        $this->assertEquals(3, \count($stack->debugStack()['xml']));
    }

    public function testOrder()
    {
        $stack = (new HandlerStack())
            ->appendClosure(FeedType::ALL, function () {
            }, 'start')
            ->appendClosure(FeedType::JSON, function () {
            }, 'appendJson')
            ->appendClosure(FeedType::HTML, function () {
            }, 'appendHtml')
            ->appendClosure(FeedType::XML, function () {
            }, 'appendXml')
            ->prependClosure(FeedType::JSON, function () {
            }, 'prependJson')
            ->prependClosure(FeedType::HTML, function () {
            }, 'prependHtml')
            ->prependClosure(FeedType::XML, function () {
            }, 'prependXml');

        $order = ['prependXml', 'start', 'appendXml'];

        foreach ($stack->debugStack()['xml'] as $middleware) {
            $match = \array_shift($order);
            $this->assertEquals(1, \preg_match('/' . $match . '/', $middleware));
        }
    }

    /**
     * @expectedException \SimplePie\Exception\MiddlewareException
     * @expectedExceptionMessage The middleware `Closure` could not be assigned to a feed type.
     */
    public function testMiddlewareException()
    {
        $stack = (new HandlerStack())
            ->appendClosure('bogus', function () {
            });
    }
}
