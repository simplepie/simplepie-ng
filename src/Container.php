<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Psr\Container\ContainerInterface;
use SimplePie\Exception\ContainerException;
use SimplePie\Exception\NotFoundException;
use Traversable;

/**
 * `SimplePie\Container` is a simple, small PSR-11 container. It implements
 * `ArrayAccess`, and has an interface which is Pimple-like, without all of
 * Pimple's more advanced features.
 *
 * Usage of this container system is NOT required, and can be replaced by any
 * other container system that is compatible with PSR-11.
 */
class Container implements ContainerInterface, IteratorAggregate, ArrayAccess, Countable
{
    /**
     * The container storage.
     *
     * @var ArrayObject
     */
    protected $container;

    /**
     * Constructs a new instance of this class.
     *
     * @param array $userSettings Settings that are passed by the user.
     */
    public function __construct()
    {
        $this->container = [];
    }

    /**
     * Creates an external iterator.
     *
     * @return Traversable
     *
     * @see IteratorAggregate
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->container);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $offset The name of the container key.
     *
     * @return bool
     *
     * @see ArrayAccess
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $offset Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface No entry was found for **this** identifier.
     *
     * @return mixed
     *
     * @see ArrayAccess
     */
    public function offsetGet($offset)
    {
        if (!isset($this->container[$offset])) {
            throw new NotFoundException(
                sprintf('The container ID `%s` does not exist.', $offset)
            );
        }

        return $this->container[$offset]($this);
    }

    /**
     * Meta-method for setting a container value.
     *
     * @param string   $offset The identifier of the value.
     * @param callable $value  An anonymous function which accepts the container as its only
     *                         parameter and returns the intended value.
     *
     * @see ArrayAccess
     *
     * @throws ContainerExceptionInterface
     */
    public function offsetSet($offset, $value): void
    {
        if (isset($this->container[$offset])) {
            throw new ContainerException(
                sprintf('The container ID `%s` cannot be overwritten.', $offset)
            );
        }

        if (!is_callable($value)) {
            throw new ContainerException(
                sprintf('The value `%s` MUST be a callable.', $offset)
            );
        }

        $this->container[$offset] = $value;
    }

    /**
     * Removes an entry from the container by identifier.
     *
     * @param string $offset The identifier to delete from the container.
     *
     * @see ArrayAccess
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Count elements of an object.
     *
     * @return int
     *
     * @see Countable
     */
    public function count(): int
    {
        return count($this->container);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     *
     * @see ContainerInterface
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     *
     * @see ContainerInterface
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
