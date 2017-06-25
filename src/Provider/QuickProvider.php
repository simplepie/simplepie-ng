<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Provider;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LogLevel;
use SimplePie\Enum\FeedType;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\Middleware\Xml\Rss;

class QuickProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        //----------------------------------------------------------------------
        // LOGGING

        $container['_.logger'] = function (Container $c) {
            $logger = new Logger('SimplePie');
            $logger->pushHandler($c['logger.handler.error_debug']);

            return $logger;
        };

        $container['logger.handler.error_debug'] = function () {
            return new ErrorLogHandler(
                ErrorLogHandler::OPERATING_SYSTEM,
                LogLevel::DEBUG,
                true,
                false
            );
        };

        //----------------------------------------------------------------------
        // MIDDLEWARE

        $container['_.middleware'] = function (Container $c) {
            $stack = new HandlerStack($c['_.logger']);

            $stack
                ->append($c['middleware.atom10'], 'atom')
                // ->append(new \SimpleXMLElement('<what/>'))
                // ->append($c['middleware.rss20'])
                // ->append($c['middleware.myCallable'], 'myCallable', FeedType::ALL)
                // ->prepend($c['middleware.groot'], 'groot', FeedType::ALL)
            ;

            return $stack;
        };

        $container['middleware.atom10'] = function (Container $c) {
            return new Atom($c['_.logger']);
        };

        $container['middleware.rss20'] = function () {
            return new Rss();
        };

        $container['middleware.myCallable'] = function () {
            return function () {
                echo __FUNCTION__ . PHP_EOL;
            };
        };

        $container['middleware.groot'] = function () {
            return function () {
                echo 'I AM GROOT!' . PHP_EOL;
            };
        };
    }
}
