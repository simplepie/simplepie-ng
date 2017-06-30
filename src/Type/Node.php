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
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\Serialization;
use SimplePie\Exception\SimplePieException;

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
    protected $value = null;

    /**
     * The serialization of the content.
     *
     * @var string
     */
    protected $serialization = Serialization::TEXT;

    /**
     * Get the text node in multiple formats.
     *
     * @param DOMNode $node A `DOMNode` element to read properties from.
     */
    public function __construct(?DOMNode $node = null)
    {
        if ($node) {
            $this->node  = $node;
            $this->value = $node->nodeValue;

            if ($node->nodeType === XML_ELEMENT_NODE && $node->attributes->length > 0) {
                foreach ($node->attributes as $attribute) {
                    if ($attribute->name === 'type' && $attribute->value === 'html') {
                        $this->serialization = $attribute->nodeValue;
                        $this->value = html_entity_decode(
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
