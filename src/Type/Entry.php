<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DOMNode;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin as Tr;
use SimplePie\Parser\Date as DateParser;

/**
 * A type model for an Entry element.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#412-the-atomentry-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#535-items
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#55-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#elements-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#items
 */
class Entry extends AbstractType implements NodeInterface, BranchInterface, C\SetLoggerInterface
{
    use Tr\DateTrait;
    use Tr\DeepTypeTrait;
    use Tr\LoggerTrait;
    use Tr\RootTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The preferred namespace alias for a given XML namespace URI. Should be
     * the result of a call to `SimplePie\Util\Ns`.
     *
     * @var string
     */
    protected $namespaceAlias;

    /**
     * Constructs a new instance of this class.
     *
     * @param string          $namespaceAlias [description]
     * @param DOMNode|null    $node           The `DOMNode` element to parse.
     * @param LoggerInterface $logger         The PSR-3 logger.
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    public function __construct(
        string $namespaceAlias,
        ?DOMNode $node = null,
        LoggerInterface $logger = null
    ) {
        // phpcs:enable

        $this->namespaceAlias = $namespaceAlias;

        if ($node) {
            $this->logger = $logger ?? new NullLogger();
            $this->node   = $node;
            $this->root   = $node;
        }
    }

    /**
     * Converts this object into a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return \sprintf('<%s: resource %s>', \get_called_class(), \md5(\spl_object_hash($this)));
    }

    /**
     * Gets the DOMNode element.
     *
     * @return DOMNode|null
     */
    public function getNode(): ?DOMNode
    {
        return $this->node;
    }

    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @return string
     */
    public function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'categories':
                return 'category';

            case 'contributors':
                return 'contributor';

            case 'language':
                return 'lang';

            case 'links':
                return 'link';

            case 'pubDate':
            case 'publishDate':
            case 'publishedDate':
                return 'published';

            default:
                return $nodeName;
        }
    }

    /**
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @throws SimplePieException
     *
     * @return mixed
     *
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getHandler(string $nodeName, array $args = [])
    {
        switch ($nodeName) {
            case 'id':
            case 'lang':
            case 'rights':
            case 'subtitle':
            case 'summary':
            case 'title':
                return $this->getScalarSingleValue($this->getRoot(), $nodeName, $args[0]);

            case 'published':
            case 'updated':
                return (new DateParser(
                    $this->getScalarSingleValue($this->getRoot(), $nodeName, $args[0])->getValue(),
                    $this->outputTimezone,
                    $this->createFromFormat
                ))->getDateTime();

            case 'author':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Person::class, $args[0]);

            case 'generator':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Generator::class, $args[0]);

            case 'category':
            case 'contributor':
            case 'link':
                return $this->getComplexMultipleValues($this->getRoot(), $nodeName, $args[0]);

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }

    // phpcs:enable
}
