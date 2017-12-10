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
use SimplePie\Configuration\SetLoggerInterface;
use SimplePie\Enum\FeedType;
use SimplePie\Exception\MiddlewareException;
use SimplePie\Middleware\Html\HtmlInterface;
use SimplePie\Middleware\Json\JsonInterface;
use SimplePie\Middleware\Xml\XmlInterface;
use SimplePie\Mixin as T;
use Skyzyx\UtilityPack\Types;
use stdClass;

/**
 * `SimplePie\HandlerStack` is a middleware stack system which is modeled after
 * [Guzzle's middleware handler stack system](http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html),
 * but is designed specifically for SimplePie's use-cases.
 *
 * Its primary job is to (a) allow the registration and priority of middleware,
 * and (b) provide the interface for SimplePie NG to trigger middleware.
 */
class HandlerStack implements HandlerStackInterface, SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * The middleware stack, grouped by feed type.
     *
     * @var string
     */
    protected $stack;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        $this->stack = [
            'html' => [],
            'json' => [],
            'xml'  => [],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function append(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): HandlerStackInterface {
        // @codingStandardsIgnoreEnd

        $this->validateMiddleware(
            $middleware,
            $name,
            $overrideType,
            static function (&$arr) use ($middleware, $name): void {
                $arr[] = [$middleware, $name];
            }
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function appendClosure(
        string $overrideType,
        callable $middleware,
        ?string $name = null
    ): HandlerStackInterface {
        // @codingStandardsIgnoreEnd

        return $this->append($middleware, $name, $overrideType);
    }

    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function prepend(
        callable $middleware,
        ?string $name = null,
        ?string $overrideType = null
    ): HandlerStackInterface {
        // @codingStandardsIgnoreEnd

        $this->validateMiddleware(
            $middleware,
            $name,
            $overrideType,
            static function (&$arr) use ($middleware, $name): void {
                \array_unshift($arr, [$middleware, $name]);
            }
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function prependClosure(
        string $overrideType,
        callable $middleware,
        ?string $name = null
    ): HandlerStackInterface {
        // @codingStandardsIgnoreEnd

        return $this->prepend($middleware, $name, $overrideType);
    }

    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function invoke(
        string $feedType,
        stdClass $feedRoot,
        ?string $namespaceAlias,
        DOMXPath $xpath
    ): void {
        // @codingStandardsIgnoreEnd

        if (isset($this->stack[$feedType])) {
            foreach ($this->stack[$feedType] as $tuple) {
                $middleware = $tuple[0];
                $middleware->setLogger($this->getLogger());
                $middleware($feedRoot, $namespaceAlias, $xpath);
            }
        } else {
            $allowedTypes = FeedType::introspectKeys();
            \array_shift($allowedTypes);

            throw new MiddlewareException(\sprintf(
                'Could not determine which handler stack to invoke. Stack `%s` was requested. [Allowed: %s]',
                $feedType,
                \implode(', ', \array_map(
                    static function ($type) {
                        return \sprintf('FeedType::%s', $type);
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
        $fn = static function ($mw) {
            return \sprintf(
                '[<%s: resource %s>, %s]',
                Types::getClassOrType($mw[0]),
                \md5(\spl_object_hash($mw[0])),
                isset($mw[1]) ? \sprintf('"%s"', $mw[1]) : 'null'
            );
        };

        return [
            'html' => \array_map($fn, $this->stack['html']),
            'json' => \array_map($fn, $this->stack['json']),
            'xml'  => \array_map($fn, $this->stack['xml']),
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

        if (FeedType::ALL === $overrideType) {
            $fn($this->stack['html']);
            $fn($this->stack['json']);
            $fn($this->stack['xml']);
        } elseif (FeedType::JSON === $overrideType || $middleware instanceof JsonInterface) {
            $fn($this->stack['json']);
        } elseif (FeedType::XML === $overrideType || $middleware instanceof XmlInterface) {
            $fn($this->stack['xml']);
        } elseif (FeedType::HTML === $overrideType || $middleware instanceof HtmlInterface) {
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

        $this->logger->info(\sprintf(
            'Registered `%s` as middleware%s.',
            Types::getClassOrType($middleware),
            (null !== $name ? \sprintf(' with the name `%s`', $name) : '')
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

        return \sprintf(
            'The middleware `%s`%s could not be assigned to a feed type.',
            Types::getClassOrType($middleware),
            (null !== $name ? \sprintf(' with the name `%s`', $name) : '')
        );
    }
}
