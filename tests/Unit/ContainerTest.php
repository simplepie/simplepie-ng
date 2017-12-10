<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use SimplePie\Container;

/**
 * @coversNothing
 */
class ContainerTest extends AbstractTestCase
{
    public function testConstruct(): void
    {
        $container1 = new Container();
        $this->assertSame(0, \count($container1));

        $container2 = new Container([]);
        $this->assertSame(0, \count($container2));

        $container3            = new Container();
        $container3['testing'] = static function (Container $c) {
            return true;
        };
        $this->assertSame(1, \count($container3));
    }

    public function testSetterOk(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        $this->assertSame(1, \count($container));
    }

    public function testSetterNotOk(): void
    {
        $this->expectException(\SimplePie\Exception\ContainerException::class);
        $this->expectExceptionMessage('The container ID `testing` cannot be overwritten.');

        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };
        $container['testing'] = 'This is gonna fail.';
    }

    public function testSetterOk2(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        unset($container['testing']);

        $container['testing'] = static function (Container $c) {
            return true;
        };

        $this->assertSame(1, \count($container));
    }

    public function testGetterNotOk(): void
    {
        $this->expectException(\SimplePie\Exception\NotFoundException::class);
        $this->expectExceptionMessage('The container ID `testing` does not exist.');

        $container = new Container();
        $get       = $container['testing'];
    }

    public function testGetterOk(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        $this->assertTrue($container['testing']);
    }

    public function testGetterOk2(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        $this->assertTrue($container->get('testing'));
    }

    public function testGetterNotOk2(): void
    {
        $this->expectException(\SimplePie\Exception\ContainerException::class);
        $this->expectExceptionMessage('The value `testing` MUST be a callable.');

        $container = new Container();

        $container['testing'] = true;

        $this->assertTrue($container['testing']);
    }

    public function testGetterNotOk3(): void
    {
        $this->expectException(\SimplePie\Exception\ContainerException::class);
        $this->expectExceptionMessage('The value `testing` MUST be a callable.');

        $container = new Container();

        $container['testing'] = 'testing';

        $this->assertTrue($container['testing']);
    }

    public function testHasOk(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        $this->assertTrue($container->has('testing'));
    }

    public function testIterator(): void
    {
        $container = new Container();

        $container['testing'] = static function (Container $c) {
            return true;
        };

        foreach ($container as $k => $v) {
            $this->assertSame($k, 'testing');
            $this->assertInternalType('callable', $v);
            $this->assertTrue($v($container));
        }
    }
}
