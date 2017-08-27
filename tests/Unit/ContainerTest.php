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

class ContainerTest extends AbstractTestCase
{
    public function testConstruct(): void
    {
        $container1 = new Container();
        $this->assertSame(0, \count($container1));

        $container2 = new Container([]);
        $this->assertSame(0, \count($container2));

        $container3            = new Container();
        $container3['testing'] = function (Container $c) {
            return true;
        };
        $this->assertSame(1, \count($container3));
    }

    public function testSetterOK(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        $this->assertSame(1, \count($container));
    }

    /**
     * @expectedException \SimplePie\Exception\ContainerException
     * @expectedExceptionMessage The container ID `testing` cannot be overwritten.
     */
    public function testSetterNotOK(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };
        $container['testing'] = 'This is gonna fail.';
    }

    public function testSetterOK2(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        unset($container['testing']);

        $container['testing'] = function (Container $c) {
            return true;
        };

        $this->assertSame(1, \count($container));
    }

    /**
     * @expectedException \SimplePie\Exception\NotFoundException
     * @expectedExceptionMessage The container ID `testing` does not exist.
     */
    public function testGetterNotOK(): void
    {
        $container = new Container();
        $get       = $container['testing'];
    }

    public function testGetterOK(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        $this->assertTrue($container['testing']);
    }

    public function testGetterOK2(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        $this->assertTrue($container->get('testing'));
    }

    /**
     * @expectedException \SimplePie\Exception\ContainerException
     * @expectedExceptionMessage The value `testing` MUST be a callable.
     */
    public function testGetterNotOK2(): void
    {
        $container = new Container();

        $container['testing'] = true;

        $this->assertTrue($container['testing']);
    }

    /**
     * @expectedException \SimplePie\Exception\ContainerException
     * @expectedExceptionMessage The value `testing` MUST be a callable.
     */
    public function testGetterNotOK3(): void
    {
        $container = new Container();

        $container['testing'] = 'testing';

        $this->assertTrue($container['testing']);
    }

    public function testHasOK(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        $this->assertTrue($container->has('testing'));
    }

    public function testIterator(): void
    {
        $container = new Container();

        $container['testing'] = function (Container $c) {
            return true;
        };

        foreach ($container as $k => $v) {
            $this->assertSame($k, 'testing');
            $this->assertInternalType('callable', $v);
            $this->assertTrue($v($container));
        }
    }
}
