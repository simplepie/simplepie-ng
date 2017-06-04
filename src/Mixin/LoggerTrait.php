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
use SimplePie\SimplePie;

trait LoggerTrait
{
    /**
     * Retrieves a PSR-3 logger.
     *
     * @return Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        $container = SimplePie::getContainer();

        return $container['_.logger'];
    }
}
