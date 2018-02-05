<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

use ReflectionClass;

/**
 * The base enum class that all other enum classes extend from. It does the
 * heavy lifting of implementing `EnumInterface` so that extending enum classes
 * can focus on defining enums.
 */
abstract class AbstractEnum implements EnumInterface
{
    /**
     * {@inheritdoc}
     */
    public static function introspect(): array
    {
        $refl = new ReflectionClass(\get_called_class());

        return $refl->getConstants();
    }

    /**
     * {@inheritdoc}
     */
    public static function introspectKeys(): array
    {
        return \array_keys(static::introspect());
    }

    /**
     * {@inheritdoc}
     */
    public static function hasValue(string $value): bool
    {
        $arr = \array_flip(static::introspect());

        return isset($arr[$value]);
    }
}
