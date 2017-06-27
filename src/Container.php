<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use Psr\Container\ContainerInterface;
use Psr\Log\NullLogger;
use SimplePie\Exception\ContainerException;
use SimplePie\Exception\NotFoundException;

class Container implements ContainerInterface
{
    /**
     * The default settings to use when a user fails to provide a specific one.
     *
     * @var array
     */
    private $defaultSettings = [
        'logger' => NullLogger::class,
    ];

    /**
     * The container storage.
     *
     * @var array
     */
    protected $container = [];

    /**
     * Constructs a new instance of this class.
     *
     * @param array $userSettings Settings that are passed by the user.
     */
    public function __construct(array $userSettings = [])
    {
        $this->container = array_merge($defaultSettings, $userSettings);
    }

    /**
     * Meta-method for setting a container value.
     *
     * @param string   $id    The identifier of the value.
     * @param callable $value An anonymous function which accepts the container as its only
     *                        parameter and returns the intended value.
     */
    public function __set(string $id, callable $value): void
    {
        if (isset($this->container[$id])) {
            throw new ContainerException(
                sprintf('The container ID `%s` cannot be overwritten.', $id)
            );
        }

        $this->container[$id] = $value;
    }

    /**
     * Meta-method for setting a container value.
     *
     * @param string $id The identifier of the value.
     *
     * @return mixed
     */
    public function __get(string $id)
    {
        return $this->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this->container[$id])) {
            throw new NotFoundException(
                sprintf('The container ID `%s` does not exist.', $id)
            );
        }

        return $this->container[$id]($this->container);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this->container[$id]);
    }
}
