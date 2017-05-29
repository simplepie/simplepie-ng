<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Mixin;

use DOMDocument;

trait DomDocumentTrait
{
    /**
     * The DOMDocument object which is being used to parse the content.
     *
     * @var DOMDocument
     */
    protected $domDocument;

    /**
     * Gets the DOMDocument object which is being used to parse the content.
     *
     * @return DOMDocument
     */
    public function getDomDocument(): DOMDocument
    {
        return $this->domDocument;
    }
}
