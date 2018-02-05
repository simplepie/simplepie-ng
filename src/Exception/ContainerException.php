<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * `ContainerException` is required as part of PSR-11 compatibility.
 */
class ContainerException extends SimplePieException implements ContainerExceptionInterface
{
}
