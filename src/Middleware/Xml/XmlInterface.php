<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Middleware\Xml;

use DOMXPath;
use SimplePie\Middleware\MiddlewareInterface;
use stdClass;

/**
 * The interface that all XML-based middleware classes should adhere to.
 */
interface XmlInterface extends MiddlewareInterface
{
    /**
     * The function signature for the middleware.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    public function __invoke(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void;

    /**
     * Gets the list of supported namespaces and their namespace aliases.
     *
     * Returns an associative array where the key is the XML Namespace URL, and the value is the friendly alias name
     * that the middleware can use to refer to it.
     *
     * ```php
     * return [
     *     'http://www.w3.org/2005/Atom' => 'atom10'
     * ];
     * ```
     *
     * @return array
     */
    public function getSupportedNamespaces(): array;
}
