<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

namespace SimplePie;

use Interop\Container\ContainerInterface;

class SimplePie
{
    /**
     * The PSR-11 dependency injection container.
     *
     * @var Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger    = $container['__sp__.logger'];
    }

    /**
     * Retrieves the PSR-11 dependency injection container object.
     *
     * @return [type] [description]
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
