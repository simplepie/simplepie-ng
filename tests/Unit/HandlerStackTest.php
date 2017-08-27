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

    public function testAppendClosure(): void
    {
        $stack = (new HandlerStack())
            ->append(new Atom())
            ->appendClosure(FeedType::ALL, function (): void {
            })
            ->appendClosure(FeedType::JSON, function (): void {
            })
            ->appendClosure(FeedType::HTML, function (): void {
            })
            ->appendClosure(FeedType::XML, function (): void {
            });

        $this->assertSame(2, \count($stack->debugStack()['json']));
        $this->assertSame(2, \count($stack->debugStack()['html']));
        $this->assertSame(3, \count($stack->debugStack()['xml']));
    }

    public function testOrder(): void
    {
        $stack = (new HandlerStack())
            ->appendClosure(FeedType::ALL, function (): void {
            }, 'start')
            ->appendClosure(FeedType::JSON, function (): void {
            }, 'appendJson')
            ->appendClosure(FeedType::HTML, function (): void {
            }, 'appendHtml')
            ->appendClosure(FeedType::XML, function (): void {
            }, 'appendXml')
            ->prependClosure(FeedType::JSON, function (): void {
            }, 'prependJson')
            ->prependClosure(FeedType::HTML, function (): void {
            }, 'prependHtml')
            ->prependClosure(FeedType::XML, function (): void {
            }, 'prependXml');

        $order = ['prependXml', 'start', 'appendXml'];

        foreach ($stack->debugStack()['xml'] as $middleware) {
            $match = \array_shift($order);
            $this->assertSame(1, \preg_match('/' . $match . '/', $middleware));
        }
    }

    /**
     * @expectedException \SimplePie\Exception\MiddlewareException
     * @expectedExceptionMessage The middleware `Closure` could not be assigned to a feed type.
     */
    public function testMiddlewareException(): void
    {
        $stack = (new HandlerStack())
            ->appendClosure('bogus', function (): void {
            });
    }
}
