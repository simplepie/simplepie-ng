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
use ReflectionClass;
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

        // Top-level feed
        $path = ['feed'];
        $this->getSingleScalarTypes($feedRoot, $namespaceAlias, $xpath, $path);
        $this->getSingleComplexTypes($feedRoot, $namespaceAlias, $xpath, $path);
        $this->getMultipleComplexTypes($feedRoot, $namespaceAlias, $xpath, $path);

        // <entry> element
        $path = ['feed', 'entry'];

        foreach ($feedRoot->entry[$namespaceAlias] as $i => &$entry) {
            $cpath   = $path;
            $cpath[] = $i;

            $this->getSingleScalarTypes($entry, $namespaceAlias, $xpath, $cpath);
            $this->getSingleComplexTypes($entry, $namespaceAlias, $xpath, $cpath);
            $this->getMultipleComplexTypes($entry, $namespaceAlias, $xpath, $cpath);
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
     * @param array    $path           The path of the XML traversal. Should begin with `<feed>` or `<channel>`,
     *                                 then `<entry>` or `<item>`.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getSingleScalarTypes(
        object $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

        $cpath = $path;
        $nodes = [];

        if (\is_int(\end($cpath))) {
            \array_pop($cpath);
        }

        if ('feed' === \end($cpath)) {
            $nodes = [
                'icon',
                'id',
                'logo',
                'published',
                'rights',
                'subtitle',
                'summary',
                'title',
                'updated',
            ];
        } elseif ('entry' === \end($cpath)) {
            $nodes = [
                'content',
                'id',
                'published',
                'rights',
                'summary',
                'title',
                'updated',
            ];
        }

        foreach ($nodes as $nodeName) {
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$nodeName]));
            $this->addArrayProperty($feedRoot, $nodeName);
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
     * @param array    $path           The path of the XML traversal. Should begin with `<feed>` or `<channel>`,
     *                                 then `<entry>` or `<item>`.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getSingleComplexTypes(
        object $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

        $cpath = $path;
        $nodes = [];

        if (\is_int(\end($cpath))) {
            \array_pop($cpath);
        }

        if ('feed' === \end($cpath)) {
            $nodes = [
                'generator' => T\Generator::class,
            ];
        }

        foreach ($nodes as $name => $class) {
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$name]));
            $xq    = $xpath->query($query);
            $this->addArrayProperty($feedRoot, $name);
            $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

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
     * @param array    $path           The path of the XML traversal. Should begin with `<feed>` or `<channel>`,
     *                                 then `<entry>` or `<item>`.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getMultipleComplexTypes(
        object $feedRoot,
        string $namespaceAlias,
        DOMXPath $xpath,
        array $path
    ): void {
        // phpcs:enable

        $cpath = $path;
        $nodes = [];

        if (\is_int(\end($cpath))) {
            \array_pop($cpath);
        }

        if ('feed' === \end($cpath)) {
            $nodes = [
                'author'      => T\Person::class,
                'category'    => T\Category::class,
                'contributor' => T\Person::class,
                'entry'       => T\Entry::class,
                'link'        => T\Link::class,
            ];
        } elseif ('entry' === \end($cpath)) {
            $nodes = [
                'author'      => T\Person::class,
                'category'    => T\Category::class,
                'contributor' => T\Person::class,
                'link'        => T\Link::class,
            ];
        }

        foreach ($nodes as $name => $class) {
            $query = $this->generateQuery($namespaceAlias, \array_merge($path, [$name]));
            $xq    = $xpath->query($query);
            $this->addArrayProperty($feedRoot, $name);
            $this->getLogger()->debug(\sprintf('%s is running an XPath query:', __CLASS__), [$query]);

            $feedRoot->{$name}[$namespaceAlias] = [];

            foreach ($xq as $result) {
                // What kind of class is this?
                $rclass = (new ReflectionClass($class))
                    ->newInstanceWithoutConstructor();

                if ($rclass instanceof T\BranchInterface) {
                    $feedRoot->{$name}[$namespaceAlias][] = new $class(
                        $namespaceAlias,
                        $result,
                        $this->getLogger()
                    );
                } else {
                    $feedRoot->{$name}[$namespaceAlias][] = new $class(
                        $result,
                        $this->getLogger()
                    );
                }
            }
        }
    }
}
