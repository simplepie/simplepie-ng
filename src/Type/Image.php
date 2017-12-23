<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DOMNode;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Mixin as T;

class Image extends AbstractType implements TypeInterface, C\SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The image element's URL.
     *
     * @var string
     */
    protected $uri;

    /**
     * The image element's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The image element's link.
     *
     * @var string
     */
    protected $link;

    /**
     * The image element's width, in pixels.
     *
     * @var int
     */
    protected $width;

    /**
     * The image element's height, in pixels.
     *
     * @var int
     */
    protected $height;

    /**
     * The image element's description.
     *
     * @var string
     */
    protected $description;

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode|null    $node   The `DOMNode` element to parse.
     * @param LoggerInterface $logger The PSR-3 logger.
     */
    public function __construct(?DOMNode $node = null, LoggerInterface $logger = null)
    {
        if ($node) {
            $this->logger      = $logger ?? new NullLogger();
            $this->node        = $node;
            $this->uri         = new Node($this->node);
            $this->title       = null;
            $this->link        = null;
            $this->width       = null;
            $this->height      = null;
            $this->description = null;
        }
    }

    /**
     * Converts this object into a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->uri ?? '';
    }

    /**
     * Gets the DOMNode element.
     *
     * @return DOMNode|null
     */
    public function getNode(): ?DOMNode
    {
        return $this->node;
    }

    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @return string
     */
    protected function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'url':
                return 'uri';

            default:
                return $nodeName;
        }
    }

    /**
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @throws SimplePieException
     *
     * @return Node
     */
    protected function getHandler(string $nodeName): Node
    {
        switch ($nodeName) {
            case 'uri':
            case 'title':
            case 'link':
            case 'width':
            case 'height':
            case 'description':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
