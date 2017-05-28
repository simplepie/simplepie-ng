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

trait LoggerTrait
{
    /**
     * The PSR-3 logger.
     *
     * @var Interop\Container\ContainerInterface
     */
    protected $logger;

    /**
     * Retrieves the PSR-3 logger.
     *
     * @return Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
