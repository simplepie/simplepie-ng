<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Mixin;

/**
 * Shared code for working with elements which manage the tree root.
 */
trait RootTrait
{
    /**
     * The root-most node in the feed.
     *
     * @var object
     */
    protected $root;

    /**
     * Retrieve the root-most node in the feed.
     *
     * @return object
     */
    public function getRoot(): object
    {
        return $this->root;
    }
}
