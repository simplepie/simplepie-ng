<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Middleware;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Mixin\LoggerTrait;

abstract class AbstractMiddleware
{
    use LoggerTrait;

    /**
     * A callable which is used to determine whether or not to run this middleware.
     *
     * @var callable
     */
    protected $fn;

    /**
     * Constructs a new instance of this class.
     *
     * Accepts a callable with the following function signature:
     *
     * ```
     * function () {}
     * ```
     *
     * @param LoggerInterface|null $logger A PSR-3 logger.
     * @param callable|null        $fn     A callable which is used to determine whether or not to run this
     *                                     middleware. A value of `true` means that the middleware should run.
     *                                     A value of `false` means that the middleware should NOT run, By
     *                                     default, the middleware will run.
     */
    public function __construct(?LoggerInterface $logger = null, ?callable $fn = null)
    {
        $this->logger = $logger ?? new NullLogger();

        $this->fn = $fn ?: function () {
            return true;
        };
    }

    /**
     * Checks whether or not a particular property exists on an object, and if not, instantiates it as an array.
     *
     * @param mixed  $object   An object that you can add ad-hoc properties to. Preferably a `stdClass` object.
     * @param string $property The name of the property to check and/or add.
     */
    public function addArrayProperty(&$object, string $property): void
    {
        if (!isset($object->$property)) {
            $object->$property = [];
        }
    }
}
