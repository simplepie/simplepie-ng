<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
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
     * The status of case-sensitivity.
     *
     * @var bool
     */
    protected $caseSensitive = true;

    /**
     * By default, SimplePie NG is case-sensitive (as per the specification). If an invalid feed is parsed that does not
     * follow the specification with regard to casing of XML elements, this method allows you to trade some performance
     * in favor of case-insensitive parsing.
     *
     * @param bool $makeInsensitive Whether or not the handling should be made case-insensitive. A value of `true`
     *                              means that the handling should be case-insensitive. A value of `false` means that
     *                              the handling should be case-sensitive. The default value is `true`.
     *
     * @return self
     */
    public function setCaseInsensitive(bool $makeInsensitive = true): self
    {
        $this->caseSensitive = !$makeInsensitive;

        return $this;
    }

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
     * ['feed', 'entry', 5, 'id', '@xml:lang']
     * ```
     *
     * ```xpath
     * /feed/entry[5]/id/@xml:lang (simplified)
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

            // Reduce to only the upper/lower of the active letters
            // ≈30-35% faster than the full alphabet
            $pLet  = \count_chars($p, 3);
            $pLow  = \mb_strtolower($pLet);
            $pUp   = \mb_strtoupper($pLet);
            $pAttr = ('@' === \mb_substr($p, 0, 1));

            if (\is_int($next)) {
                if ($this->caseSensitive || $pAttr) {
                    // case; next
                    $query .= \sprintf(
                        '/%s%s[position() = %d]',
                        (!$pAttr
                            ? $namespaceAlias . ':'
                            : ''),
                        $p,
                        \array_shift($path) + 1
                    );
                } else {
                    // icase; next
                    $query .= \sprintf(
                        '/%s*[translate(name(), \'%s\', \'%s\') = \'%s\'][position() = %d]',
                        (!$pAttr
                            ? $namespaceAlias . ':'
                            : ''),
                        $pUp,
                        $pLow,
                        $p,
                        \array_shift($path) + 1
                    );
                }
            } else {
                if ($this->caseSensitive || $pAttr) {
                    // case; no-next
                    $query .= \sprintf(
                        '/%s%s',
                        (!$pAttr
                            ? $namespaceAlias . ':'
                            : ''),
                        $p
                    );
                } else {
                    // icase; no-next
                    $query .= \sprintf(
                        '/%s*[translate(name(), \'%s\', \'%s\') = \'%s\']',
                        (!$pAttr
                            ? $namespaceAlias . ':'
                            : ''),
                        $pUp,
                        $pLow,
                        $p
                    );
                }
            }
        }

        return $query;
    }
}
