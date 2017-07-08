<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Psr\Log\LoggerInterface;
use SimplePie\Container;
use SimplePie\Configuration;
use SimplePie\HandlerStackInterface;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;

class ConfigurationTest extends AbstractTestCase
{
    public function testDefaultContainer()
    {
        Configuration::setContainer();

        $this->assertTrue(Configuration::getLogger() instanceof LoggerInterface);
        $this->assertTrue(Configuration::getMiddlewareStack() instanceof HandlerStackInterface);
        $this->assertEquals(4792582, Configuration::getLibxml());
    }

    public function testCustomContainer()
    {
        $container = new Container();

        $container['simplepie.logger'] = function (Container $c)
        {
            $logger = new Logger('Testing');
            $logger->pushHandler(new TestHandler());

            return $logger;
        };

        $container['simplepie.libxml'] = function (Container $c)
        {
            return LIBXML_NOCDATA;
        };

        $container['simplepie.middleware'] = function (Container $c)
        {
            return (new HandlerStack())->append(new Atom());
        };

        Configuration::setContainer($container);

        $this->assertTrue(Configuration::getLogger() instanceof LoggerInterface);
        $this->assertTrue(Configuration::getMiddlewareStack() instanceof HandlerStackInterface);
        $this->assertEquals(16384, Configuration::getLibxml());
    }
}
