<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Mixin;

trait LibxmlTrait
{
    /**
     * Bitwise libxml options to use for parsing XML.
     *
     * @var int
     */
    protected $libxml;

    /**
     * Gets the libxml value to use for parsing XML.
     *
     * @return int
     */
    public function getLibxml(): int
    {
        return $this->libxml;
    }
}
