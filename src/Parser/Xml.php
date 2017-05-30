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
use Psr\Http\Message\StreamInterface;
use ReflectionClass;
use SimplePie\Dom;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Exception\ConfigurationException;
use SimplePie\Mixin\DomDocumentTrait;
use SimplePie\Mixin\RawDocumentTrait;
use SimplePie\SimplePie;
use Throwable;

class Xml extends AbstractParser
{
    use DomDocumentTrait;
    use RawDocumentTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by
     *                                the `getBody()` method of a `ResponseInterface` class.
     *
     * @throws Error
     * @throws TypeError
     * @throws ConfigurationException
     */
    public function __construct(StreamInterface $stream)
    {
        // Container
        $this->container = SimplePie::getContainer();

        // Raw stream
        $this->rawDocument = $this->readStream($stream);

        // DOMDocument
        $this->domDocument = new DOMDocument();

        // Handle registerNodeClass() calls
        foreach ($this->container['__sp__.dom.extend.__matches__'] as $baseClass => $extendingClass) {
            try {
                if ((new ReflectionClass($extendingClass))->implementsInterface(DomInterface::class)) {
                    $this->domDocument->registerNodeClass(sprintf('DOM%s', $baseClass), $extendingClass);
                } else {
                    throw new ConfigurationException(sprintf(
                        ErrorMessage::DOM_NOT_EXTEND_FROM,
                        $baseClass,
                        $extendingClass,
                        $baseClass
                    ));
                }
            } catch (Throwable $e) {
                throw $e;
            }
        }

        // DOMDocument configuration
        $this->domDocument->formatOutput       = true;
        $this->domDocument->preserveWhiteSpace = false;
        $this->domDocument->substituteEntities = true;
        $this->domDocument->loadXML($this->rawDocument, $this->container['__sp__.dom.libxml']);
        $this->domDocument->normalizeDocument();
    }
}
