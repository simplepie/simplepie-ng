<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Type;

use Psr\Log\LoggerInterface;
use SimplePie\Mixin\LoggerTrait;

class Entry extends AbstractType implements TypeInterface
{
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param LoggerInterface $logger A PSR-3 logger.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
