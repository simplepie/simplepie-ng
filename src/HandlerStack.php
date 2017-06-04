<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use DOMXPath;
use Psr\Log\LoggerInterface;
use SimplePie\Enum\FeedType;
use SimplePie\Exception\MiddlewareException;
use SimplePie\Middleware\Html\HtmlInterface;
use SimplePie\Middleware\Json\JsonInterface;
use SimplePie\Middleware\Xml\XmlInterface;
use Skyzyx\UtilityPack\Types;
use stdClass;

class HandlerStack
{
    /**
     * A PSR-3 logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The middleware stack, grouped by feed type.
     *
     * @var string
     */
    protected $stack;

    /**
     * Constructs a new instance of this class.
     *
     * @param LoggerInterface $logger A PSR-3 logger.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->stack = [
            'html' => [],
            'json' => [],
            'xml'  => [],
        ];
    }

    /**
     * Appends a new middleware class onto the end of the stack.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function append(callable $middleware, ?string $name = null, ?string $overrideType = null): self
    {
        $this->validateMiddleware($middleware, $name, $overrideType, function (&$arr) use ($middleware, $name) {
            $arr[] = [$middleware, $name];
        });

        $this->logRegistration($middleware, $name, $overrideType);

        return $this;
    }

    /**
     * Appends a new middleware closure onto the end of the stack.
     *
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function appendClosure(string $overrideType, callable $middleware, ?string $name = null): self
    {
        return $this->append($middleware, $name, $overrideType);
    }

    /**
     * Prepends a new middleware class onto the beginning of the stack.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function prepend(callable $middleware, ?string $name = null, ?string $overrideType = null): self
    {
        $this->validateMiddleware($middleware, $name, $overrideType, function (&$arr) use ($middleware, $name) {
            array_unshift($arr, [$middleware, $name]);
        });

        $this->logRegistration($middleware, $name, $overrideType);

        return $this;
    }

    /**
     * Prepends a new middleware closure onto the beginning of the stack.
     *
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     *
     * @throws MiddlewareException
     *
     * @return self
     */
    public function prependClosure(string $overrideType, callable $middleware, ?string $name = null): self
    {
        return $this->prepend($middleware, $name, $overrideType);
    }

    /**
     * Invokes the stack of middleware.
     *
     * @param string   $feedType       A valid _single_ feed type from `SimplePie\Enum\FeedType`. Since `FeedType::ALL`
     *                                 represents _multiple_ feed types, an exception will be thrown if it is used.
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Dictionary\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    public function invoke(string $feedType, stdClass $feedRoot, ?string $namespaceAlias, DOMXPath $xpath): void
    {
        if (isset($this->stack[$feedType])) {
            foreach ($this->stack[$feedType] as $tuple) {
                $middleware = $tuple[0];
                $middleware($feedRoot, $namespaceAlias, $xpath);
            }
        } else {
            $allowedTypes = FeedType::introspectKeys();
            array_shift($allowedTypes);

            throw new MiddlewareException(sprintf(
                'Could not determine which handler stack to invoke. Stack `%s` was requested. [Allowed: %s]',
                $feedType,
                implode(', ', array_map(
                    function ($type) {
                        return sprintf('FeedType::%s', $type);
                    },
                    $allowedTypes
                ))
            ));
        }
    }

    /**
     * [debugStack description].
     *
     * @return [type] [description]
     */
    public function debugStack(): array
    {
        $fn = function ($mw) {
            return sprintf(
                '[<%s: resource %s>, %s]',
                Types::getClassOrType($mw[0]),
                md5(spl_object_hash($mw[0])),
                isset($mw[1]) ? sprintf('"%s"', $mw[1]) : 'null'
            );
        };

        return [
            'html' => array_map($fn, $this->stack['html']),
            'json' => array_map($fn, $this->stack['json']),
            'xml'  => array_map($fn, $this->stack['xml']),
        ];
    }

    /**
     * Validates the middleware and applies it to the right stack.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     * @param callable    $fn           A callable which receives the stack by-reference as a parameter, and chooses
     *                                  how to add the middleware to that stack.
     *
     * @throws MiddlewareException
     *
     * @codingStandardsIgnoreStart
     */
    protected function validateMiddleware(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null,
        callable $fn
    ): void {
        // @codingStandardsIgnoreEnd

        if ($overrideType === FeedType::ALL) {
            $fn($this->stack['html']);
            $fn($this->stack['json']);
            $fn($this->stack['xml']);
        } elseif ($overrideType === FeedType::JSON || $middleware instanceof JsonInterface) {
            $fn($this->stack['json']);
        } elseif ($overrideType === FeedType::XML || $middleware instanceof XmlInterface) {
            $fn($this->stack['xml']);
        } elseif ($overrideType === FeedType::HTML || $middleware instanceof HtmlInterface) {
            $fn($this->stack['html']);
        } else {
            throw new MiddlewareException($this->exceptionMessage($middleware, $name, $overrideType));
        }
    }

    /**
     * Log that the registration of the middleware occurred.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @codingStandardsIgnoreStart
     */
    protected function logRegistration(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): void {
        // @codingStandardsIgnoreEnd

        $this->logger->info(sprintf(
            'Registered `%s` as middleware%s.',
            Types::getClassOrType($middleware),
            (!is_null($name) ? sprintf(' with the name `%s`', $name) : '')
        ));
    }

    /**
     * Generate the most appropriate error message based on the parameters that were passed.
     *
     * @param callable    $middleware   The middleware to add to the stack.
     * @param string|null $name         A name for the middleware. Can be used with `pushBefore()` and `pushAfter()`.
     * @param string|null $overrideType Override our best guess for which stack to apply the middleware to. By default
     *                                  the appropriate stack will be determined by which
     *                                  `SimplePie\Middleware\*\*Interface` the middleware extends from. If the
     *                                  middleware is a closure, this parameter is required. If the appropriate stack
     *                                  cannot be determined, a `SimplePie\Exception\MiddlewareException` exception
     *                                  will be thrown.
     *
     * @return string
     *
     * @codingStandardsIgnoreStart
     */
    protected function exceptionMessage(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): string {
        // @codingStandardsIgnoreEnd

        return sprintf(
            'The middleware `%s`%s could not be assigned to a feed type.',
            Types::getClassOrType($middleware),
            (!is_null($name) ? sprintf(' with the name `%s`', $name) : '')
        );
    }
}
