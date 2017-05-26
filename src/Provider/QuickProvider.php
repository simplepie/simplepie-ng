<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

namespace SimplePie\Provider;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LogLevel;

class QuickProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['logger'] = function (Container $c) {
            $logger = new Logger('SimplePie');
            $logger->pushHandler($c['logger.handler.error_debug']);

            return $logger;
        };

        $container['logger.handler.error_debug'] = function (Container $c) {
            return new ErrorLogHandler(
                ErrorLogHandler::OPERATING_SYSTEM,
                LogLevel::DEBUG,
                true,
                false
            );
        };

        #-----------------------------------------------------------------------

        $container['__sp__.logger'] = $container['logger'];
    }
}
