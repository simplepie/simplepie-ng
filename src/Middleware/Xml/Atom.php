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
use SimplePie\Configuration as C;
use SimplePie\Mixin as T;
use SimplePie\Type\Generator;
use SimplePie\Type\Node;
use SimplePie\Type\Person;
use stdClass;

/**
 * Support for the Atom 1.0 grammar.
 *
 * @see https://tools.ietf.org/html/rfc4287
 * @see https://www.w3.org/wiki/Atom
 */
class Atom extends AbstractXmlMiddleware implements XmlInterface, C\SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        // lang (single, scalar)
        $this->addArrayProperty($feedRoot, 'lang');
        $xq = $xpath->query($this->applyNsToQuery('/%s:feed[attribute::xml:lang][1]/@xml:lang', $namespaceAlias));

        $feedRoot->lang[$namespaceAlias] = ($xq->length > 0)
            ? Node::factory((string) $xq->item(0)->nodeValue)
            : null;

        // single, scalar
        foreach (['id', 'rights', 'subtitle', 'summary', 'title'] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->{$nodeName}[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }

        // time/date stamps
        foreach (['published', 'updated'] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->{$nodeName}[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }

        // generator (single, complex)
        $this->addArrayProperty($feedRoot, 'generator');
        $xq = $xpath->query($this->generateQuery($namespaceAlias, true, 'feed', 'generator'));

        $feedRoot->generator[$namespaceAlias] = ($xq->length > 0)
            ? new Generator($xq->item(0), $this->getLogger())
            : null;

        // author (single, complex)
        $this->addArrayProperty($feedRoot, 'author');
        $xq = $xpath->query($this->generateQuery($namespaceAlias, true, 'feed', 'author'));

        $feedRoot->author[$namespaceAlias] = ($xq->length > 0)
            ? new Person($xq->item(0), $this->getLogger())
            : null;

        // contributor (multiple, complex)
        $this->addArrayProperty($feedRoot, 'contributor');
        $xq = $xpath->query($this->generateQuery($namespaceAlias, true, 'feed', 'contributor'));

        $feedRoot->contributor[$namespaceAlias] = [];

        foreach ($xq as $result) {
            $feedRoot->contributor[$namespaceAlias][] = new Person($result, $this->getLogger());
        }
    }

    /**
     * Find and read the contents of the feed-level nodes which only have a single value.
     *
     * @param string   $nodeName       The name of the namespaced XML node to read.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     *
     * @return Node
     */
    protected function getSingle(string $nodeName, string $namespaceAlias, DOMXPath $xpath): Node
    {
        $query = $this->generateQuery($namespaceAlias, true, 'feed', $nodeName);
        $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

        return $this->handleSingleNode(static function () use ($xpath, $query) {
            return $xpath->query($query);
        });
    }
}
