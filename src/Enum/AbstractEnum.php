<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

use ReflectionClass;

abstract class AbstractEnum implements EnumInterface
{
    /**
     * {@inheritdoc}
     */
    public static function introspect(): array
    {
        $refl = new ReflectionClass(get_called_class());

        return $refl->getConstants();
    }

    /**
     * {@inheritdoc}
     */
    public static function introspectKeys(): array
    {
        return array_keys(static::introspect());
    }
}
