<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

use SimplePie\HandlerStackInterface;

/**
 * Shared code for working with middleware.
 */
trait MiddlewareStackTrait
{
    /**
     * The handler stack which contains registered middleware.
     *
     * @var HandlerStackInterface
     */
    protected $middleware;

    /**
     * Gets the handler stack which contains registered middleware.
     *
     * @return HandlerStackInterface
     */
    public function getMiddlewareStack(): HandlerStackInterface
    {
        return $this->middleware;
    }
}
