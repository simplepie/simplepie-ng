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
use SimplePie\Mixin\LoggerTrait;
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

    //---------------------------------------------------------------------------
    // SINGLE VALUES

    public function getId(?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias
            ?? $this->namespaceAlias;

        if (isset($this->getRoot()->id[$alias])) {
            return $this->getRoot()->id[$alias];
        }

        return new Node();
    }

    public function getSummary(?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias
            ?? $this->namespaceAlias;

        if (isset($this->getRoot()->summary[$alias])) {
            return $this->getRoot()->summary[$alias];
        }

        return new Node();
    }

    //---------------------------------------------------------------------------
    // MULTIPLE VALUES


    //---------------------------------------------------------------------------
    // INTERNAL

    public function getItems()
    {
    }

    //---------------------------------------------------------------------------
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
