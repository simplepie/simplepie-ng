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
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\UidProcessor;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LogLevel;

class QuickProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['logger'] = function (Container $c) {
            $logger = new Logger('SimplePie');
            // $logger->pushProcessor(new IntrospectionProcessor());
            // $logger->pushProcessor(new MemoryUsageProcessor());
            // $logger->pushProcessor(new ProcessIdProcessor());
            // $logger->pushProcessor(new UidProcessor());
            // $logger->pushProcessor(new PsrLogMessageProcessor());
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

        //-----------------------------------------------------------------------

        // $container['__sp__.dom.extend.DOMAttr']
        // $container['__sp__.dom.extend.DOMCdataSection']
        // $container['__sp__.dom.extend.DOMCharacterData']
        // $container['__sp__.dom.extend.DOMComment']
        // $container['__sp__.dom.extend.DOMDocument']
        // $container['__sp__.dom.extend.DOMDocumentFragment']
        // $container['__sp__.dom.extend.DOMDocumentType']
        // $container['__sp__.dom.extend.DOMElement']
        // $container['__sp__.dom.extend.DOMEntity']
        // $container['__sp__.dom.extend.DOMEntityReference']
        // $container['__sp__.dom.extend.DOMException']
        // $container['__sp__.dom.extend.DOMImplementation']
        // $container['__sp__.dom.extend.DOMNamedNodeMap']
        // $container['__sp__.dom.extend.DOMNode']
        // $container['__sp__.dom.extend.DOMNodeList']
        // $container['__sp__.dom.extend.DOMNotation']
        // $container['__sp__.dom.extend.DOMProcessingInstruction']
        // $container['__sp__.dom.extend.DOMText']
        // $container['__sp__.dom.extend.DOMXPath']
        // $container['__sp__.dom.libxml']
        $container['__sp__.logger'] = $container['logger'];
    }
}
