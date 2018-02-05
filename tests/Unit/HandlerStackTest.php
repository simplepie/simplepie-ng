<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
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
            ->appendClosure(FeedType::ALL, static function (): void {
            })
            ->appendClosure(FeedType::JSON, static function (): void {
            })
            ->appendClosure(FeedType::HTML, static function (): void {
            })
            ->appendClosure(FeedType::XML, static function (): void {
            });

        $this->assertSame(2, \count($stack->debugStack()['json']));
        $this->assertSame(2, \count($stack->debugStack()['html']));
        $this->assertSame(3, \count($stack->debugStack()['xml']));
    }

    public function testOrder(): void
    {
        $stack = (new HandlerStack())
            ->appendClosure(FeedType::ALL, static function (): void {
            }, 'start')
            ->appendClosure(FeedType::JSON, static function (): void {
            }, 'appendJson')
            ->appendClosure(FeedType::HTML, static function (): void {
            }, 'appendHtml')
            ->appendClosure(FeedType::XML, static function (): void {
            }, 'appendXml')
            ->prependClosure(FeedType::JSON, static function (): void {
            }, 'prependJson')
            ->prependClosure(FeedType::HTML, static function (): void {
            }, 'prependHtml')
            ->prependClosure(FeedType::XML, static function (): void {
            }, 'prependXml');

        $order = ['prependXml', 'start', 'appendXml'];

        foreach ($stack->debugStack()['xml'] as $middleware) {
            $match = \array_shift($order);
            $this->assertSame(1, \preg_match('/' . $match . '/', $middleware));
        }
    }

    public function testMiddlewareException(): void
    {
        $this->expectException(\SimplePie\Exception\MiddlewareException::class);
        $this->expectExceptionMessage('The middleware `Closure` could not be assigned to a feed type.');

        $stack = (new HandlerStack())
            ->appendClosure('bogus', static function (): void {
            });
    }
}
