<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SimplePie\Configuration;
use SimplePie\Container;
use SimplePie\Exception\ConfigurationException;
use SimplePie\HandlerStack;
use SimplePie\HandlerStackInterface;
use SimplePie\Middleware\Xml\Atom;

/**
 * @coversNothing
 */
class ConfigurationTest extends AbstractTestCase
{
    public function testDefaultContainer(): void
    {
        Configuration::setContainer();

        $this->assertTrue(Configuration::getLogger() instanceof LoggerInterface);
        $this->assertTrue(Configuration::getMiddlewareStack() instanceof HandlerStackInterface);
        $this->assertSame(4792582, Configuration::getLibxml());
    }

    public function testCustomContainer(): void
    {
        $container = new Container();

        $container['simplepie.logger'] = static function () {
            $logger = new Logger('Testing');
            $logger->pushHandler(new TestHandler());

            return $logger;
        };

        $container['simplepie.libxml'] = static function () {
            return LIBXML_NOCDATA;
        };

        $container['simplepie.middleware'] = static function () {
            return (new HandlerStack())->append(new Atom());
        };

        Configuration::setContainer($container);

        $this->assertTrue(Configuration::getLogger() instanceof LoggerInterface);
        $this->assertTrue(Configuration::getMiddlewareStack() instanceof HandlerStackInterface);
        $this->assertSame(16384, Configuration::getLibxml());
    }

    public function testCustomLogger(): void
    {
        $message = 'The configured logger MUST be compatible with `Psr\\Log\\LoggerInterface`. '
            . 'Received `string` instead.';

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage($message);

        $container = new Container();

        $container['simplepie.logger'] = static function (Container $c) {
            return 'b0rk';
        };

        Configuration::setContainer($container);
    }

    public function testCustomLibxml(): void
    {
        $message = 'The configured libxml options MUST be bitwise LIBXML_* constants, which result in an '
            . 'integer value. Received `string` instead.';

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage($message);

        $container = new Container();

        $container['simplepie.libxml'] = static function (Container $c) {
            return 'b0rk';
        };

        Configuration::setContainer($container);
    }

    public function testCustomMiddleware(): void
    {
        $message = 'The configured middleware handler stack MUST be compatible with '
            . '`SimplePie\\HandlerStackInterface`. Received `string` instead.';

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage($message);

        $container = new Container();

        $container['simplepie.middleware'] = static function (Container $c) {
            return 'b0rk';
        };

        Configuration::setContainer($container);
    }
}
