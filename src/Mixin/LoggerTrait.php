<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Mixin;

use Psr\Log\LoggerInterface;
use Skyzyx\UtilityPack\Types;

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
     * Sets the PSR-3 logger.
     *
     * @param LoggerInterface $logger
     *
     * @return self
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        // What are we logging with?
        $this->logger->debug(\sprintf(
            'Class `%s` configured to use `%s`.',
            Types::getClassOrType($this),
            Types::getClassOrType($this->logger)
        ));

        return $this;
    }

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
