<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

class CharacterSet extends AbstractEnum
{
    public const ASCII      = 'us-ascii';
    public const ISO_8859_1 = 'iso-8859-1';
    public const US_ASCII   = 'us-ascii';
    public const UTF8       = 'utf-8';
    public const UTF_8      = 'utf-8';
    public const WIN_1252   = 'windows-1252';
}
