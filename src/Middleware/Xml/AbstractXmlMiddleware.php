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

abstract class AbstractXmlMiddleware extends AbstractMiddleware
{
    /**
     * Replace all instances of `%s` with the `$namespaceAlias` parameter.
     *
     * This is similar to `sprintf()`, but the `$namespaceAlias` is applied to _all_ instances of `%s`.
     *
     * @param string $query          An XPath query where `%s` is used in-place of the XML namespace alias.
     * @param string $namespaceAlias The XML namespace alias to apply.
     *
     * @return string
     */
    public function applyNs(string $query, string $namespaceAlias): string
    {
        return str_replace('%s', $namespaceAlias, $query);
    }

    /**
     * Some elements in the feed should only have one result. This handles those cases.
     *
     * @param callable $fn A callable which returns a `DOMElementList`.
     *
     * @return array Returns an array with keys of `text` and `html`.
     */
    public function handleSingleNode(callable $fn): array
    {
        $nodes = $fn();

        if ($nodes->length > 0) {
            return [
                'text' => $nodes[0]->textContent,
                'html' => $nodes[0]->nodeValue,
            ];
        }

        return [
            'text' => null,
            'html' => null,
        ];
    }

    // public function handleMultipleNodes()
    // {

    // }
}
