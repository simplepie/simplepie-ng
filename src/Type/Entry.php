<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use SimplePie\Configuration;
use SimplePie\Mixin\LoggerTrait;

class Entry extends AbstractType implements TypeInterface
{
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        $this->logger = Configuration::getLogger();
    }
}
