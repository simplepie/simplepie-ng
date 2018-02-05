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
use SimplePie\Exception\SimplePieException;

/**
 * A type model for a deep-level Node element.
 */
class Node extends AbstractType implements NodeInterface, TypeInterface
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
     * The language of the content.
     *
     * @var string|null
     */
    protected $lang;

    /**
     * The xml:base value of the content.
     *
     * @var string|null
     */
    protected $base;

    /**
     * The serialization of the content.
     *
     * @var string
     */
    protected $serialization = Serialization::TEXT;

    /**
     * Get the text node in multiple formats.
     *
     * @param DOMNode|null $node     A `DOMNode` element to read properties from.
     * @param array        $fallback An array of attributes for default XML attributes. The default value is an
     *                               empty array.
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function __construct(?DOMNode $node = null, array $fallback = [])
    {
        if ($node) {
            $this->node  = $node;
            $this->value = $node->nodeValue;

            if (isset($fallback['base'])) {
                $this->base = $fallback['base']->nodeValue;
            }

            if (isset($fallback['lang'])) {
                $this->lang = $fallback['lang']->nodeValue;
            }

            if (XML_ELEMENT_NODE === $node->nodeType && $node->attributes->length > 0) {
                foreach ($node->attributes as $attribute) {
                    if ('xml:base' === $attribute->nodeName) {
                        $this->base = $attribute->nodeValue;
                    } elseif ('xml:lang' === $attribute->nodeName) {
                        $this->lang = $attribute->nodeValue;
                    } elseif ('src' === $attribute->name) {
                        $this->handleAsSource($attribute);
                    } elseif ('type' === $attribute->name && Serialization::TEXT === $attribute->value) {
                        $this->handleAsText($node, $attribute);
                    } elseif ('type' === $attribute->name && Serialization::HTML === $attribute->value) {
                        $this->handleAsHtml($node, $attribute);
                    } elseif ('type' === $attribute->name && Serialization::XHTML === $attribute->value) {
                        $this->handleAsXhtml($node, $attribute);
                    } elseif ('type' === $attribute->name && 'application/octet-stream' === $attribute->value) {
                        $this->handleAsBase64($node);
                    } else {
                        $this->serialization = Serialization::TEXT;
                        $this->value         = $node->nodeValue;
                    }
                }
            }
        }
    }

    // @phpcs:enable

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
     * Gets the language of the content.
     *
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->lang;
    }

    /**
     * Gets the xml:base of the content.
     *
     * @return string|null
     */
    public function getBase(): ?string
    {
        return $this->base;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'language':
                return 'lang';

            default:
                return $nodeName;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $nodeName, array $args = []): self
    {
        throw new SimplePieException(
            $this->getUnresolvableMessage($nodeName)
        );
    }

    /**
     * Handle the content as source.
     *
     * @param DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsSource(DOMAttr $attribute): void
    {
        $this->serialization = Serialization::TEXT;
        $this->value         = $attribute->nodeValue;
    }

    /**
     * Handle the content as plain text.
     *
     * @param DOMNode $node      The DOMNode element.
     * @param DOMAttr $attribute The DOMAttr element.
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
     * @param DOMNode $node      The DOMNode element.
     * @param DOMAttr $attribute The DOMAttr element.
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
     * @param DOMNode $node      The DOMNode element.
     * @param DOMAttr $attribute The DOMAttr element.
     */
    private function handleAsXhtml(DOMNode $node, DOMAttr $attribute): void
    {
        $this->serialization = $attribute->nodeValue;

        // We can't just grab the content. We need to stringify it, then remove the wrapper element.
        $content = \preg_replace(
            '/^<div xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\">(.*)<\/div>$/ims',
            '$1',
            $node->ownerDocument->saveXML(
                $node->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'div')[0]
            )
        );

        $this->value = \trim($content);
    }

    /**
     * Handle the content as Base64-encoded text.
     *
     * @param DOMNode $node The DOMNode element.
     */
    private function handleAsBase64(DOMNode $node): void
    {
        $this->serialization = Serialization::TEXT;
        $this->value         = \base64_decode(\trim($node->nodeValue), true);
    }
}
