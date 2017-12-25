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
use SimplePie\Configuration as C;
use SimplePie\Mixin as Tr;
use SimplePie\Type as T;
use stdClass;

/**
 * Support for the Atom 1.0 grammar.
 *
 * @see https://tools.ietf.org/html/rfc4287
 * @see https://www.w3.org/wiki/Atom
 */
class Atom extends AbstractXmlMiddleware implements XmlInterface, C\SetLoggerInterface
{
    use Tr\LoggerTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        // lang (single, scalar)
        $this->addArrayProperty($feedRoot, 'lang');
        $xq = $xpath->query($this->applyNsToQuery('/%s:feed[attribute::xml:lang][1]/@xml:lang', $namespaceAlias));

        $feedRoot->lang[$namespaceAlias] = ($xq->length > 0)
            ? T\Node::factory((string) $xq->item(0)->nodeValue)
            : null;

        $this->getSingleScalarTypes($feedRoot, $namespaceAlias, $xpath);
        $this->getSingleComplexTypes($feedRoot, $namespaceAlias, $xpath);
        $this->getMultipleComplexTypes($feedRoot, $namespaceAlias, $xpath);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNamespaces(): array
    {
        return [
            'http://www.w3.org/2005/Atom' => 'atom10',
        ];
    }

    /**
     * Fetches elements with a single, scalar value.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    protected function getSingleScalarTypes(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        foreach ([
            'icon',
            'id',
            'logo',
            'published',
            'rights',
            'subtitle',
            'summary',
            'title',
            'updated',
        ] as $nodeName) {
            $this->addArrayProperty($feedRoot, $nodeName);
            $feedRoot->{$nodeName}[$namespaceAlias] = $this->getSingle($nodeName, $namespaceAlias, $xpath);
        }
    }

    /**
     * Fetches elements with a single, complex value.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    protected function getSingleComplexTypes(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        foreach ([
            'author' => T\Person::class,
            'generator' => T\Generator::class,
        ] as $name => $class) {
            $this->addArrayProperty($feedRoot, $name);
            $xq = $xpath->query($this->generateQuery($namespaceAlias, 'feed', $name));

            $feedRoot->{$name}[$namespaceAlias] = ($xq->length > 0)
                ? new $class($xq->item(0), $this->getLogger())
                : null;
        }
    }

    /**
     * Fetches elements with a multiple, complex values.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     */
    protected function getMultipleComplexTypes(stdClass $feedRoot, string $namespaceAlias, DOMXPath $xpath): void
    {
        foreach ([
            'category' => T\Category::class,
            'contributor' => T\Person::class,
            'link' => T\Link::class,
        ] as $name => $class) {
            $this->addArrayProperty($feedRoot, $name);
            $xq = $xpath->query($this->generateQuery($namespaceAlias, 'feed', $name));

            $feedRoot->{$name}[$namespaceAlias] = [];

            foreach ($xq as $result) {
                $feedRoot->{$name}[$namespaceAlias][] = new $class($result, $this->getLogger());
            }
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
    protected function getSingle(string $nodeName, string $namespaceAlias, DOMXPath $xpath): T\Node
    {
        $query = $this->generateQuery($namespaceAlias, 'feed', $nodeName);
        $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

        return $this->handleSingleNode(static function () use ($xpath, $query) {
            return $xpath->query($query);
        });
    }
}
