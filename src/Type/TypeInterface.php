<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Type;

/**
 * The interface that all type classes must implement and respond to.
 */
interface TypeInterface
{
    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @return string
     *
     * @internal
     */
    public function getAlias(string $nodeName): string;

    /**
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     The parameters which are passed to the method.
     *
     * @throws SimplePieException
     *
     * @return Node
     *
     * @internal
     */
    public function getHandler(string $nodeName, array $args = []): Node;
}
