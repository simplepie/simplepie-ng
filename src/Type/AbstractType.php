<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

abstract class AbstractType
{
    /**
     * Proxy method which forwards requests to an underlying handler.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @return mixed
     *
     * @codingStandardsIgnoreStart
     *
     *
     * @codingStandardsIgnoreEnd
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

        $nodeName = $this->getAlias($nodeName);

        return $this->getHandler($nodeName, $args);
    }

    /**
     * Gets the standard, pre-formatted message for unresolvable method calls.
     *
     * @param string $nodeName The short version of the call (without the `get`).
     *
     * @return string
     */
    protected function getUnresolvableMessage(string $nodeName): string
    {
        return \sprintf(
            '%s is an unresolvable method.',
            \sprintf('get%s', \ucfirst($nodeName))
        );
    }
}
