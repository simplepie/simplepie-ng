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
use SimplePie\Exception\SimplePieException;

class Node extends AbstractType implements TypeInterface
{
    /**
     * The HTML serialization of the content.
     *
     * @var string|null
     */
    protected $html = null;

    /**
     * The Plain Text serialization of the content.
     *
     * @var string|null
     */
    protected $text = null;

    /**
     * Get the text node in multiple formats.
     *
     * @param DOMNode $node A `DOMNode` element to read properties from.
     */
    public function __construct(?DOMNode $node = null)
    {
        if ($node) {
            $this->text = $node->textContent;
            $this->html = $node->nodeValue;
        }
    }

    /**
     * Meta-method which enables the return of inaccessible properties.
     *
     * @param string $name The name of the property to retrieve.
     *
     * @throws SimplePieException
     *
     * @return string|null
     */
    public function __get(string $name): ?string
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new SimplePieException(
            sprintf(
                'The property `%s` is invalid and cannot be accessed.',
                $name
            )
        );
    }
}
