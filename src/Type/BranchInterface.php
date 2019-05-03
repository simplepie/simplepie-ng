<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

/**
 * The interface that all type classes must implement and respond to.
 */
interface BranchInterface
{
    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @internal
     */
    public function getAlias(string $nodeName): string;

    /**
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     The arguments that are passed to the method.
     *
     * @throws SimplePieException
     *
     * @return Node
     *
     * @internal
     */
    public function getHandler(string $nodeName, array $args = []);
}
