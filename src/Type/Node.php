<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DOMAttr;
use DOMNode;
use DOMText;
use SimplePie\Enum\CharacterSet;
use SimplePie\Enum\Serialization;

/**
 * A type model for a deep-level Node element.
 */
class Node extends AbstractType implements NodeInterface
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
                    if ('src' === $attribute->name) {
                        $this->handleAsSource($node, $attribute);
                        break;
                    } elseif ('type' === $attribute->name && Serialization::TEXT === $attribute->value) {
                        $this->handleAsText($node, $attribute);
                        break;
                    } elseif ('type' === $attribute->name && Serialization::HTML === $attribute->value) {
                        $this->handleAsHtml($node, $attribute);
                        break;
                    } elseif ('type' === $attribute->name && Serialization::XHTML === $attribute->value) {
                        $this->handleAsXhtml($node, $attribute);
                        break;
                    } elseif ('type' === $attribute->name && 'application/octet-stream' === $attribute->value) {
                        $this->handleAsBase64($node);
                        break;
                    } else {
                        $this->serialization = Serialization::TEXT;
                        $this->value         = $node->nodeValue;
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

    /**
     * Handle the content as source.
     *
     * @param  DOMNode $node      The DOMNode element.
     * @param  DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsSource(DOMNode $node, DOMAttr $attribute): void
    {
        $this->serialization = Serialization::TEXT;
        $this->value         = $attribute->nodeValue;
    }

    /**
     * Handle the content as plain text.
     *
     * @param  DOMNode $node      The DOMNode element.
     * @param  DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsText(DOMNode $node, DOMAttr $attribute): void
    {
        $this->serialization = $attribute->nodeValue;
        $this->value         = \html_entity_decode(
            $node->nodeValue,
            ENT_COMPAT,
            CharacterSet::UTF_8
        );
    }

    /**
     * Handle the content as HTML.
     *
     * @param  DOMNode $node      The DOMNode element.
     * @param  DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsHtml(DOMNode $node, DOMAttr $attribute): void
    {
        $this->serialization = $attribute->nodeValue;
        $this->value         = \html_entity_decode(
            $node->nodeValue,
            ENT_COMPAT | ENT_HTML5,
            CharacterSet::UTF_8
        );
    }

    /**
     * Handle the content as XHTML.
     *
     * @param  DOMNode $node      The DOMNode element.
     * @param  DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsXhtml(DOMNode $node, DOMAttr $attribute): void
    {
        $this->serialization = $attribute->nodeValue;

        // We can't just grab the content. We need to stringify it, then remove the wrapper element.
        $content = preg_replace(
            '/^<div xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\">(.*)<\/div>$/ims',
            '$1',
            $node->ownerDocument->saveXML(
                $node->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'div')[0]
            )
        );

        $this->value = trim($content);
    }

    /**
     * Handle the content as Base64-encoded text.
     *
     * @param  DOMNode $node      The DOMNode element.
     */
    private function handleAsBase64(DOMNode $node): void
    {
        $this->serialization = Serialization::TEXT;
        $this->value = base64_decode(trim($node->nodeValue));
    }
}
