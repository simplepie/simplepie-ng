<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Util;

use DOMDocument;
use Psr\Log\NullLogger;
use SimplePie\Mixin as Tr;

/**
 * Provides tools for managing and working with XML namespaces.
 */
class Ns
{
    use Tr\DomDocumentTrait;
    use Tr\LoggerTrait;

    /**
     * A mapping of namespace URIs to preferred namespace aliases.
     *
     * @var array
     */
    protected $mapping = [];

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMDocument $dom A DOMDocument object representing the XML file to be parsed.
     */
    public function __construct(DOMDocument $dom)
    {
        $this->logger      = new NullLogger();
        $this->domDocument = $dom;
        $this->mapping     = [];
    }

    /**
     * Adds new aliases to this list of aliases. Set an associative array where
     * the key is the namespace URI and the namespace alias as the value.
     *
     * @param array $aliases An associative array of namespace URIs to namespace aliases.
     *
     * @return array The updated list of namespace aliases.
     */
    public function addAliases(array $aliases): array
    {
        $this->mapping = \array_merge($this->mapping, $aliases);
        $this->getLogger()->info('Added namespace URIs and namespace aliases.', $this->mapping);

        return $this->mapping;
    }

    /**
     * Gets the list of document-defined namespace aliases and namespace URIs.
     *
     * @return array
     */
    public function getDocNamespaces(): array
    {
        return \simplexml_import_dom($this->domDocument)->getDocNamespaces(true, true);
    }

    /**
     * Gets the preferred namespace alias for a particular feed dialect.
     *
     * @param string|null $namespaceUri The namespace URI used inside the XML document. If the value is `null`, then
     *                                  the preferred namespace alias of the root namespace will be returned. The
     *                                  default value is `null`.
     *
     * @return string
     */
    public function getPreferredNamespaceAlias(?string $namespaceUri = null): string
    {
        $namespaceUri = $namespaceUri
            ?? $this->domDocument->documentElement->namespaceURI
            ?? '';

        // If the namespace URI has an exact match, use it.
        if (isset($this->mapping[$namespaceUri])) {
            return $this->mapping[$namespaceUri];
        }

        // If not an exact match, attempt a regex match.
        foreach ($this->mapping as $regex => $alias) {
            // Regex delimiter must not be alphanumeric or backslash. Check this first.
            // phpcs:disable Generic.Files.LineLength.MaxExceeded
            if (!empty($regex) && 0 === \preg_match('/[0-9a-z\\\\]/i', $regex[0]) && 0 !== \preg_match($regex, $namespaceUri)) {
                // phpcs:enable
                return $alias;
            }
        }

        return null;
    }
}
