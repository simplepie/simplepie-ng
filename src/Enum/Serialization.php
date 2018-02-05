<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

/**
 * Provides a set of known, allowable serializations of content nodes.
 */
class Serialization extends AbstractEnum
{
    public const TEXT = 'text';

    public const HTML = 'html';

    public const XHTML = 'xhtml';
}
