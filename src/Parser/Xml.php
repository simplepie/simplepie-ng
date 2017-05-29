<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Parser;

use DOMDocument;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use SimplePie\Mixin\ContainerTrait;
use SimplePie\Mixin\DomDocumentTrait;
use SimplePie\Mixin\RawDocumentTrait;
use Throwable;

class Xml extends AbstractParser
{
    use ContainerTrait;
    use DomDocumentTrait;
    use RawDocumentTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     * @param StreamInterface    $stream    A PSR-7 `StreamInterface` which is typically returned by
     *                                      the `getBody()` method of a `ResponseInterface` class.
     *
     * @throws Error
     * @throws TypeError
     */
    public function __construct(ContainerInterface $container, StreamInterface $stream)
    {
        // Container
        $this->container = $container;

        // Raw stream
        $this->rawDocument = $this->readStream($stream);

        // DOMDocument
        $this->domDocument = new DOMDocument();

        // Handle registerNodeClass() calls
        if ($container->has('__sp__.dom.extend.__matches__')) {
            foreach ($container['__sp__.dom.extend.__matches__'] as $baseClass => $extendingClass) {
                try {
                    $this->domDocument->registerNodeClass($baseClass, $extendingClass);
                } catch (Throwable $e) {
                    throw $e;
                }
            }
        }

        // DOMDocument configuration
        $this->domDocument->formatOutput       = true;
        $this->domDocument->preserveWhiteSpace = false;
        $this->domDocument->substituteEntities = true;
        $this->domDocument->loadXML($this->rawDocument, $this->container['__sp__.dom.libxml']);
    }
}
