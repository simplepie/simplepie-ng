<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * `NotFoundException` is required as part of PSR-11 compatibility.
 */
class NotFoundException extends SimplePieException implements NotFoundExceptionInterface
{
}
