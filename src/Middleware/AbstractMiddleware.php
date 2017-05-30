<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Middleware;

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
     * @param callable|null $fn A callable which is used to determine whether or not to run this
     *                          middleware. A value of `true` means that the middleware should run.
     *                          A value of `false` means that the middleware should NOT run, By
     *                          default, the middleware will run.
     */
    public function __construct(?callable $fn = null)
    {
        $this->fn = $fn ?: function () {
            return true;
        };
    }

    /**
     * Enables this instance to be called as a function.
     *
     * @param DOMXPath $xpath   The instantiated `DOMXPath` object for this document.
     * @param callable $next    The next middleware in the stack.
     * @param array    $options Options passed-in from the calling environment that the middleware can use.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.invoke
     */
    public function __invoke(DOMXPath $xpath, callable $next, array $options)
    {
        $callable = $this->fn;

        if ($callable()) {
            $this->process($xpath, $next);
        }
    }
}
