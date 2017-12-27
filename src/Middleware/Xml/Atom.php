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
        $path = [];

        // lang (single, scalar)
        $this->addArrayProperty($feedRoot, 'lang');
        $xq = $xpath->query($this->applyNsToQuery('/%s:feed[attribute::xml:lang][1]/@xml:lang', $namespaceAlias));

        $feedRoot->lang[$namespaceAlias] = ($xq->length > 0)
            ? T\Node::factory((string) $xq->item(0)->nodeValue)
            : null;

        foreach (['feed', 'entry'] as $p) {
            $path[] = $p;

            $this->getSingleScalarTypes($feedRoot, $namespaceAlias, $xpath, $path);
            $this->getSingleComplexTypes($feedRoot, $namespaceAlias, $xpath, $path);
            $this->getMultipleComplexTypes($feedRoot, $namespaceAlias, $xpath, $path);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Supports valid and invalid variations.
     *
     * * http://www.w3.org/2005/Atom
     * * http://www.w3.org/2005/Atom/
     * * https://www.w3.org/2005/Atom
     * * https://www.w3.org/2005/Atom/
     */
    public function getSupportedNamespaces(): array
    {
        return [
            'http://www.w3.org/2005/Atom'              => 'atom10',
            '/https?:\/\/www\.w3\.org\/2005\/Atom\/?/' => 'atom10',
        ];
    }

    /**
     * Fetches elements with a single, scalar value.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     * @param array $path
     */
    protected function getSingleScalarTypes(
        stdClass $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

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
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$nodeName]));
            $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

            $feedRoot->{$nodeName}[$namespaceAlias] = $this->handleSingleNode(
                static function () use ($xpath, $query) {
                    return $xpath->query($query);
                }
            );
        }
    }

    /**
     * Fetches elements with a single, complex value.
     *
     * @param stdClass $feedRoot       The root of the feed. This will be written-to when the parsing middleware runs.
     * @param string   $namespaceAlias The preferred namespace alias for a given XML namespace URI. Should be the result
     *                                 of a call to `SimplePie\Util\Ns`.
     * @param DOMXPath $xpath          The `DOMXPath` object with this middleware's namespace alias applied.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     * @param array $path
     */
    protected function getSingleComplexTypes(
        stdClass $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

        foreach ([
            'author' => T\Person::class,
            'generator' => T\Generator::class,
        ] as $name => $class) {
            $this->addArrayProperty($feedRoot, $name);
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$name]));
            $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);
            $xq = $xpath->query($query);

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
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     * @param array $path
     */
    protected function getMultipleComplexTypes(
        stdClass $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

        foreach ([
            'category' => T\Category::class,
            'contributor' => T\Person::class,
            'link' => T\Link::class,
            'entry' => T\Entry::class,
        ] as $name => $class) {
            $this->addArrayProperty($feedRoot, $name);
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$name]));
            $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);
            $xq = $xpath->query($query);

            $feedRoot->{$name}[$namespaceAlias] = [];

            foreach ($xq as $result) {
                $feedRoot->{$name}[$namespaceAlias][] = new $class($result, $this->getLogger());
            }
        }
    }
}
