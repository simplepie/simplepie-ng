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

/**
 * The interface that all type classes must implement and respond to.
 */
interface NodeInterface
{
    /**
     * Converts this object into a string representation.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Gets the DOMNode element.
     *
     * @return DOMNode|null
     */
    public function getNode(): ?DOMNode;
}
