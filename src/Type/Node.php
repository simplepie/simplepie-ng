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
use DOMText;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\Serialization;

class Node extends AbstractType implements TypeInterface
{
    /**
     * The raw `DOMNode` element.
     *
     * @var DOMNode|null
     */
    protected $node;

    /**
     * The content of the node, serialized appropriately.
     *
     * @var string|null
     */
    protected $value;

    /**
     * The serialization of the content.
     *
     * @var string
     */
    protected $serialization = Serialization::TEXT;

    /**
     * Get the text node in multiple formats.
     *
     * @param DOMNode|null $node A `DOMNode` element to read properties from.
     */
    public function __construct(?DOMNode $node = null)
    {
        if ($node) {
            $this->node  = $node;
            $this->value = $node->nodeValue;

            if (XML_ELEMENT_NODE === $node->nodeType && $node->attributes->length > 0) {
                foreach ($node->attributes as $attribute) {
                    if ('type' === $attribute->name && 'html' === $attribute->value) {
                        $this->serialization = $attribute->nodeValue;
                        $this->value         = \html_entity_decode(
                            $node->nodeValue,
                            ENT_COMPAT | ENT_HTML5,
                            CharacterSet::UTF_8
                        );

                        break;
                    }
                }
            }
        }
    }

    /**
     * Casting this Node element to a string with return the _value_ of the Node.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue() ?? '';
    }

    /**
     * Creates a new `Node` object from a string of text (such as from an XML attribute).
     *
     * @param string $value The string of text to convert to a `Node` object.
     *
     * @return Node
     */
    public static function factory(string $value): self
    {
        return new self(
            new DOMText($value)
        );
    }

    /**
     * Gets the raw `DOMNode` element.
     *
     * @return DOMNode|null
     */
    public function getNode(): ?DOMNode
    {
        return $this->node;
    }

    /**
     * Gets the content of the node, serialized appropriately.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Gets the serialization of the content.
     *
     * Will always be one of the enums from `SimplePie\Enum\Serialization`.
     *
     * @return string
     */
    public function getSerialization(): string
    {
        return $this->serialization;
    }
}
