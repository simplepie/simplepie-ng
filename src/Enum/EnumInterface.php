<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

interface EnumInterface
{
    /**
     * Introspects the Enum for it's values.
     *
     * @return array An associative array where the constant name is the key and the constant value is the value.
     */
    public static function introspect(): array;

    /**
     * Introspects the Enum for it's keys.
     *
     * @return array An indexed array of constant names.
     */
    public static function introspectKeys(): array;
}
