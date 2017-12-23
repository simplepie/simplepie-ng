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
     * @param string $namespaceAlias   The XML namespace alias to apply.
     * @param bool   $supportMixedCase Whether or not to generate an XPath query which supports
     *                                 mixed-case/case-insensitive XML element names.
     * @param string ...$path          A variadic parameter which accepts the names of the XML
     *                                 tree nodes in sequence.
     *
     * @return string An XPath 1.0 expression.
     *
     * @see https://wiki.php.net/rfc/variadics
     * @see http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list
     */
    public function generateQuery(string $namespaceAlias, bool $supportMixedCase = false, string ...$path): string
    {
        $query = '';

        foreach ($path as $p) {
            if ($supportMixedCase) {
                $query .= \sprintf(
                    '/%s:*[translate(name(), \'%s\', \'%s\') = \'%s\']',
                    $namespaceAlias,
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    'abcdefghijklmnopqrstuvwxyz',
                    $p
                );
            } else {
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

    public function handleMultipleNodes(callable $fn): Node
    {
        $nodes = $fn();

        \print_r($nodes);
    }
}
