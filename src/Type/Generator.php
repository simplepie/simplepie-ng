<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DOMNode;
use SimplePie\Configuration;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Type\Node;

class Generator extends AbstractType implements TypeInterface
{
    use LoggerTrait;

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
     * @param DOMNode|null $node The `DOMNode` element to parse.
     */
    public function __construct(?DOMNode $node = null)
    {
        if ($node) {
            $this->logger = Configuration::getLogger();
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
     * @return DOMNode
     */
    public function getNode(): DOMNode
    {
        return $this->node;
    }

    /**
     * Gets the generator name.
     *
     * @return string
     */
    public function getName(): Node
    {
        return $this->name;
    }

    /**
     * Gets the generator URI.
     *
     * @return string
     */
    public function getUri(): Node
    {
        return $this->uri;
    }

    /**
     * Gets the generator version.
     *
     * @return string
     */
    public function getVersion(): Node
    {
        return $this->version;
    }
}
