<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Type;

use stdClass;

/**
 * Represents the top-level of a feed.
 */
class Feed
{
    /**
     * The root-most node in the feed.
     *
     * @var [type]
     */
    protected $root;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        $this->root = new stdClass();
    }

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
