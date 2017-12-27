<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

use SimplePie\Configuration as C;
use SimplePie\Type\Node;
use SimplePie\Type\TypeInterface;
use Skyzyx\UtilityPack\Types;
use stdClass;

/**
 * Shared code for working with deeply-nested elements for types.
 */
trait DeepTypeTrait
{
    /**
     * Retrieves nodes that are simple scalars, and there is only one allowed value.
     *
     * @param stdClass    $root           [description]
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return Node
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getScalarSingleValue(
        stdClass $root,
        string $nodeName,
        ?string $namespaceAlias = null
    ): Node {
        // phpcs:enable

        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($root->{$nodeName}[$alias])) {
            return $root->{$nodeName}[$alias];
        }

        return new Node();
    }

    /**
     * Retrieves nodes that are complex types, and there is only one allowed value.
     *
     * @param stdClass    $root           [description]
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string      $className      The class name to instantiate when there is not a defined value.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return TypeInterface
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getComplexSingleValue(
        stdClass $root,
        string $nodeName,
        string $className,
        ?string $namespaceAlias = null
    ): TypeInterface {
        // phpcs:enable

        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($root->{$nodeName}[$alias])) {
            return new $className($root->{$nodeName}[$alias]->getNode());
        }

        return new $className();
    }

    /**
     * Retrieves nodes that are complex types, and there may be are more than one value.
     *
     * @param stdClass    $root           [description]
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return iterable
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getComplexMultipleValues(
        stdClass $root,
        string $nodeName,
        ?string $namespaceAlias = null
    ): iterable {
        // phpcs:enable

        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($root->{$nodeName}[$alias])) {
            return $root->{$nodeName}[$alias];
        }

        return [];
    }
}
