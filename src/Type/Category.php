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

/**
 * A type model for a Category/Tag/Topic element.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#422-the-atomcategory-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#category-sub-element-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#items
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#itunescategory
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS#mediacategory
 */
class Category extends AbstractType implements TypeInterface, C\SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The category term.
     *
     * @var string
     */
    protected $term;

    /**
     * The category scheme URI.
     *
     * @var string
     */
    protected $scheme;

    /**
     * The category label.
     *
     * @var string
     */
    protected $label;

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
        return \trim(
            (string) ($this->label ?? $this->term)
        );
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
            case 'term':
            case 'scheme':
            case 'label':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
