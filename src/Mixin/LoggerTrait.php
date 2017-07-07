<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

use Psr\Log\LoggerInterface;

/**
 * Shared code for working with the logger.
 */
trait LoggerTrait
{
    /**
     * A PSR-3 logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Retrieves the PSR-3 logger.
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
