<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Middleware\Xml;

use DOMXPath;
use stdClass;

/**
 * Support for the Atom 1.0 grammar.
 *
 * @see https://tools.ietf.org/html/rfc4287
 * @see https://www.w3.org/wiki/Atom
 */
class Atom extends AbstractXmlMiddleware implements XmlInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        foreach (['id', 'rights', 'subtitle', 'summary', 'title'] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->$nodeName[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }
    }

    /**
     * Find and read the contents of the feed-level id node.
     *
     * @param string   $nodeName       The name of the namespaced XML node to read.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Dictionary\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     *
     * @return array Returns an array with keys of `text` and `html`.
     */
    protected function getSingle(string $nodeName, string $namespaceAlias, DOMXPath $xpath): array
    {
        $query = $this->applyNs('/%s:feed/%s:' . $nodeName, $namespaceAlias);
        $this->getLogger()->debug(sprintf('%s is running an XPath query:', __CLASS__), [$query]);

        return $this->handleSingleNode(function () use ($xpath, $query) {
            return $xpath->query($query);
        });
    }
}
