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
 * The base type class that all other type classes extend from. It handles low-level functionality that is shared
 * across all type classes.
 */
abstract class AbstractType
{
    /**
     * Proxy method which forwards requests to an underlying handler.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @internal
     */
    public function __call(string $nodeName, array $args)
    {
        // Make sure we have *something*
        if (empty($args)) {
            $args[0] = null;
        }

        // Strip `get` from the start of the node name.
        if ('get' === \mb_substr($nodeName, 0, 3)) {
            $nodeName = \lcfirst(\mb_substr($nodeName, 3));
        }

        /** @scrutinizer ignore-call */
        $nodeName = $this->getAlias($nodeName);

        return $this->/* @scrutinizer ignore-call */getHandler($nodeName, $args);
    }

    /**
     * Gets the standard, pre-formatted message for unresolvable method calls.
     *
     * @param string $nodeName The short version of the call (without the `get`).
     *
     * @internal
     */
    protected function getUnresolvableMessage(string $nodeName): string
    {
        return \sprintf(
            '%s is an unresolvable method.',
            \sprintf('get%s', \ucfirst($nodeName))
        );
    }
}
