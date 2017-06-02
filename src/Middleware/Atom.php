<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Middleware;

use DOMXPath;

class Atom extends AbstractMiddleware implements MiddlewareInterface
{
    /**
     * [process description].
     *
     * @param DOMXPath $xpath   [description]
     * @param callable $next    [description]
     * @param array    $options [description]
     *
     * @see https://tools.ietf.org/html/rfc4287
     */
    public function process(DOMXPath $xpath, callable $next, array $options): void
    {
        $this->logger->debug(__CLASS__);
        echo (string) $xpath->query('/atom10:feed/atom10:title')[0]->nodeValue;
    }
}
