<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use SimplePie\Configuration;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Type\Generator;
use stdClass;

/**
 * Represents the top-level of a feed.
 */
class Feed extends AbstractType implements TypeInterface
{
    use LoggerTrait;

    /**
     * The root-most node in the feed.
     *
     * @var stdClass
     */
    protected $root;

    /**
     * The preferred namespace alias for a given XML namespace URI. Should be
     * the result of a call to `SimplePie\Util\Ns`.
     *
     * @var string
     */
    protected $namespaceAlias;

    /**
     * Constructs a new instance of this class.
     *
     * @param string $namespaceAlias [description]
     */
    public function __construct(string $namespaceAlias)
    {
        $this->root           = new stdClass();
        $this->logger         = Configuration::getLogger();
        $this->namespaceAlias = $namespaceAlias;
    }

    //--------------------------------------------------------------------------
    // SINGLE VALUES

    /**
     * Retrieves nodes that are simple scalars, and there are only one allowed value.
     *
     * @param  string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                     looking at the response from `getRoot()`.
     * @param  string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return Node
     */
    public function getScalarSingleValue(string $nodeName, ?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias
            ?? $this->namespaceAlias;

        if (isset($this->getRoot()->$nodeName[$alias])) {
            return $this->getRoot()->$nodeName[$alias];
        }

        return new Node();
    }

    public function __call(string $nodeName, array $args): Node
    {
        // Make sure we have *something*
        if (empty($args)) {
            $args[0] = null;
        }

        // Strip `get` from the start of the node name.
        if (substr($nodeName, 0, 3) === 'get') {
            $nodeName = lcfirst(substr($nodeName, 3));
        }

        switch ($nodeName) {
            case 'id':
            case 'lang':
            case 'rights':
            case 'subtitle':
            case 'summary':
            case 'title':
                return $this->getScalarSingleValue($nodeName, $args[0]);
            default:
                throw new SimplePieException(
                    sprintf('%s is an unresolvable method.')
                );
        }
    }

    //--------------------------------------------------------------------------
    // MULTIPLE VALUES

    public function getGenerator(?string $namespaceAlias = null): Generator
    {
        $alias = $namespaceAlias
            ?? $this->namespaceAlias;

        if (isset($this->getRoot()->generator[$alias])) {
            return $this->getRoot()->generator[$alias];
        }

        return new Generator();
    }

    //--------------------------------------------------------------------------
    // INTERNAL

    public function getItems(): void
    {
    }

    //--------------------------------------------------------------------------
    // INTERNAL

    /**
     * Retrieve the root-most node in the feed.
     *
     * @return stdClass
     */
    public function getRoot(): stdClass
    {
        return $this->root;
    }
}
