<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

/**
 * Shared code for working with raw feed content.
 */
trait RawDocumentTrait
{
    /**
     * The raw, unparsed contents of the feed's stream.
     *
     * @var string
     */
    protected $rawDocument;

    /**
     * Retrieves the raw, unparsed contents of the feed's stream.
     *
     * @return string
     */
    public function getRawDocument(): string
    {
        return $this->rawDocument;
    }
}
