<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Middleware\Xml;

use SimplePie\Middleware\AbstractMiddleware;
use SimplePie\Type\Node;

/**
 * The base XML middleware class that all other XML middleware classes extend from. It handles low-level functionality
 * that is shared across all XML middleware classes.
 */
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
    public function applyNsToQuery(string $query, string $namespaceAlias): string
    {
        return \str_replace('%s', $namespaceAlias, $query);
    }

    /**
     * Produce an XPath 1.0 expression which is used to query XML document nodes.
     *
     * ```php
     * ['feed', 'entry', 5, 'id']
     * ```
     *
     * ```xpath
     * /feed/entry[5]/id (simplified)
     * ```
     *
     * @param string $namespaceAlias The XML namespace alias to apply.
     * @param array  $path           An ordered array of nested elements, starting from the top-level XML node.
     *                               If an integer is added, then it is assumed that the element before it should be
     *                               handled as an array and the integer is its index. Expression is generated
     *                               left-to-right.
     *
     * @return string An XPath 1.0 expression.
     */
    public function generateQuery(string $namespaceAlias, array $path): string
    {
        $query = '';

        while (\count($path)) {
            $p    = \array_shift($path);
            $next = $path[0] ?? null;

            if (\is_int($next)) {
                // $query .= \sprintf(
                //     '/%s:*[translate(name(), \'%s\', \'%s\') = \'%s\'][position() = %d]',
                //     $namespaceAlias,
                //     'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                //     'abcdefghijklmnopqrstuvwxyz',
                //     $p,
                //     \array_shift($path) + 1
                // );
                $query .= \sprintf(
                    '/%s:%s[position() = %d]',
                    $namespaceAlias,
                    $p,
                    \array_shift($path) + 1
                );
            } else {
                // $query .= \sprintf(
                //     '/%s:*[translate(name(), \'%s\', \'%s\') = \'%s\']',
                //     $namespaceAlias,
                //     'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                //     'abcdefghijklmnopqrstuvwxyz',
                //     $p
                // );
                $query .= \sprintf(
                    '/%s:%s',
                    $namespaceAlias,
                    $p
                );
            }
        }

        return $query;
    }

    /**
     * Some elements in the feed should only have one result. This handles those cases.
     *
     * @param callable $fn A callable which returns a `DOMElementList`.
     *
     * @return array Returns an array with keys of `text` and `html`.
     */
    public function handleSingleNode(callable $fn): Node
    {
        $nodes = $fn();

        if ($nodes->length > 0) {
            return new Node($nodes[0]);
        }

        return new Node();
    }
}
