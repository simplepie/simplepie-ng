<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Mixin;

use Interop\Container\ContainerInterface;

trait ContainerTrait
{
    /**
     * The PSR-11 dependency injection container.
     *
     * @var Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Retrieves the PSR-11 dependency injection container object.
     *
     * @return Interop\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
