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
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin as T;

class Generator extends AbstractType implements TypeInterface, C\SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The generator name.
     *
     * @var string
     */
    protected $name;

    /**
     * The generator URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * The generator version.
     *
     * @var string
     */
    protected $version;

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode|null    $node   The `DOMNode` element to parse.
     * @param LoggerInterface $logger The PSR-3 logger.
     */
    public function __construct(?DOMNode $node = null, LoggerInterface $logger = null)
    {
        if ($node) {
            $this->logger = $logger ?? new NullLogger();
            $this->node   = $node;
            $this->name   = new Node($this->node);

            foreach ($this->node->attributes as $attribute) {
                $this->{$attribute->name} = new Node($attribute);
            }
        }
    }

    /**
     * Converts this object into a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return \trim(\sprintf('%s %s', $this->name, $this->version));
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
            case 'name':
            case 'uri':
            case 'version':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
