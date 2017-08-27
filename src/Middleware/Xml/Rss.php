<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Middleware\Xml;

use SimplePie\Middleware\AbstractMiddleware;

class Rss extends AbstractMiddleware implements XmlInterface
{
    public function __invoke(): void
    {
        echo __CLASS__ . PHP_EOL;
    }
}
