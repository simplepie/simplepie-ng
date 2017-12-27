<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DOMNode;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Parser\Date as DateParser;

/**
 * A type model for an Entry element.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#412-the-atomentry-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#535-items
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#55-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#elements-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#items
 */
class Entry extends AbstractType implements TypeInterface, C\SetLoggerInterface
{
    use LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode|null    $node   The `DOMNode` element to parse.
     * @param LoggerInterface $logger The PSR-3 logger.
     */
    public function __construct(?DOMNode $node = null, LoggerInterface $logger = null)
    {
        if ($node) {
            $this->logger = $logger ?? new NullLogger();
            $this->node   = $node;

            // foreach ($this->node->attributes as $attribute) {
            //     $this->{$attribute->name} = new Node($attribute);
            // }
        }
    }

    /**
     * Converts this object into a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('<%s: resource %s>', \get_called_class(), \md5(\spl_object_hash($this)));
    }

    /**
     * Gets the DOMNode element.
     *
     * @return DOMNode|null
     */
    public function getNode(): ?DOMNode
    {
        return $this->node;
    }

    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @return string
     */
    protected function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'categories':
                return 'category';

            case 'contributors':
                return 'contributor';

            case 'language':
                return 'lang';

            case 'links':
                return 'link';

            case 'pubDate':
            case 'publishDate':
            case 'publishedDate':
                return 'published';

            default:
                return $nodeName;
        }
    }

    /**
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @throws SimplePieException
     *
     * @return mixed
     *
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    protected function getHandler(string $nodeName, array $args)
    {
        switch ($nodeName) {
            case 'id':
            case 'lang':
            case 'rights':
            case 'subtitle':
            case 'summary':
            case 'title':
                return $this->getScalarSingleValue($nodeName, $args[0]);

            case 'published':
            case 'updated':
                return (new DateParser(
                    $this->getScalarSingleValue($nodeName, $args[0])->getValue(),
                    $this->outputTimezone,
                    $this->createFromFormat
                ))->getDateTime();

            case 'author':
                return $this->getComplexSingleValue($nodeName, Person::class, $args[0]);

            case 'generator':
                return $this->getComplexSingleValue($nodeName, Generator::class, $args[0]);

            case 'category':
            case 'contributor':
            case 'link':
                return $this->getComplexMultipleValues($nodeName, $args[0]);

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }

    // phpcs:enable

    /**
     * Retrieves nodes that are simple scalars, and there is only one allowed value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return Node
     */
    protected function getScalarSingleValue(string $nodeName, ?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->item->{$nodeName}[$alias])) {
            return $this->getRoot()->item->{$nodeName}[$alias];
        }

        return new Node();
    }

    /**
     * Retrieves nodes that are complex types, and there is only one allowed value.
     *
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
        string $nodeName,
        string $className,
        ?string $namespaceAlias = null
    ): TypeInterface {
        // phpcs:enable

        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->item->{$nodeName}[$alias])) {
            return new $className($this->getRoot()->item->{$nodeName}[$alias]->getNode());
        }

        return new $className();
    }

    /**
     * Retrieves nodes that are complex types, and there may be are more than one value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return iterable
     */
    protected function getComplexMultipleValues(string $nodeName, ?string $namespaceAlias = null): iterable
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->item->{$nodeName}[$alias])) {
            return $this->getRoot()->item->{$nodeName}[$alias];
        }

        return [];
    }
}
