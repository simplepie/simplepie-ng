<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use DateTime;
use DateTimeZone;
use SimplePie\Configuration;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Parser\Date as DateParser;
use stdClass;

/**
 * Represents the top-level of a feed.
 */
class Feed extends AbstractType implements TypeInterface
{
    use LoggerTrait;

    /**
     * The root-most node in the feed.
     *
     * @var stdClass
     */
    protected $root;

    /**
     * The preferred namespace alias for a given XML namespace URI. Should be
     * the result of a call to `SimplePie\Util\Ns`.
     *
     * @var string
     */
    protected $namespaceAlias;

    /**
     * The format that should be used when determining how to parse a date from a date string.
     *
     * @var string
     */
    protected $createFromFormat;

    /**
     * The preferred timezone to use for date output.
     *
     * @var DateTimeZone
     */
    protected $outputTimezone;

    /**
     * Constructs a new instance of this class.
     *
     * @param string $namespaceAlias [description]
     */
    public function __construct(string $namespaceAlias)
    {
        $this->root           = new stdClass();
        $this->logger         = Configuration::getLogger();
        $this->namespaceAlias = $namespaceAlias;
    }

    /**
     * Proxy method which forwards requests to an underlying handler.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @return mixed
     *
     * @codingStandardsIgnoreStart
     *
     * @method \DateTime getPublished(?string $namespaceAlias = null) Indicates an instant-in-time associated with an event early in the life-cycle of the entry.
     * @method \DateTime getUpdated(?string $namespaceAlias = null) Indicates the most recent instant-in-time when an entry or feed was modified in a way the publisher considers significant. Therefore, not all modifications necessarily result in a changed value.
     * @method SimplePie\Type\Node getId(?string $namespaceAlias = null) Conveys a permanent, universally unique identifier for an entry or feed.
     * @method SimplePie\Type\Node getLang(?string $namespaceAlias = null) Indicates the natural language for the element and its descendents.
     * @method SimplePie\Type\Node getRights(?string $namespaceAlias = null) Conveys information about rights held in and over an entry or feed.
     * @method SimplePie\Type\Node getSubtitle(?string $namespaceAlias = null) Conveys a human-readable description or subtitle for a feed.
     * @method SimplePie\Type\Node getSummary(?string $namespaceAlias = null) Conveys a short summary, abstract, or excerpt of an entry.
     * @method SimplePie\Type\Node getTitle(?string $namespaceAlias = null) Conveys a human-readable title for an entry or feed.
     * @method SimplePie\Type\Generator getGenerator(?string $namespaceAlias = null) Identifies the agent used to generate a feed, for debugging and other purposes.
     *
     * @codingStandardsIgnoreEnd
     */
    public function __call(string $nodeName, array $args)
    {
        // Make sure we have *something*
        if (empty($args)) {
            $args[0] = null;
        }

        // Strip `get` from the start of the node name.
        if ('get' === \mb_substr($nodeName, 0, 3)) {
            $nodeName = \lcfirst(\mb_substr($nodeName, 3));
        }

        $nodeName = $this->getAlias($nodeName);

        return $this->getHandler($nodeName);
    }

    /**
     * Allows the user to help the date parser by providing the format of the datestamp in the feed.
     *
     * This will be passed into `DateTime::createFromFormat()` at parse-time.
     *
     * @param string $createFromFormat The format of the datestamp in the feed.
     *
     * @return self
     *
     * @see http://php.net/manual/en/datetime.createfromformat.php
     */
    public function setDateFormat(string $createFromFormat): self
    {
        $this->createFromFormat = $createFromFormat;

        return $this;
    }

    /**
     * Set the preferred output timezone.
     *
     * This calculation is performed on a _best-effort_ basis and is not guaranteed. Factors which may affect the
     * calculation include:
     *
     * * the version of glibc/musl that your OS relies on
     * * the freshness of the timestamp data your OS relies on
     * * the format of the datestamp inside of the feed and PHP's ability to parse it
     *
     * @param string $timezone The timezone identifier to use. Must be compatible with `DateTimeZone`. The default
     *                         value is `UTC`.
     *
     * @return self
     */
    public function setOutputTimezone(string $timezone = 'UTC'): self
    {
        $this->outputTimezone = $timezone;

        return $this;
    }

    /**
     * Retrieves nodes that are simple scalars, and there are only one allowed value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return Node
     */
    public function getScalarSingleValue(string $nodeName, ?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->{$nodeName}[$alias])) {
            return $this->getRoot()->{$nodeName}[$alias];
        }

        return new Node();
    }

    //--------------------------------------------------------------------------
    // SINGLE COMPLEX VALUES

    public function getGenerator(?string $namespaceAlias = null): Generator
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->generator[$alias])) {
            return $this->getRoot()->generator[$alias];
        }

        return new Generator();
    }

    //--------------------------------------------------------------------------

    public function getItems(): void
    {
    }

    //--------------------------------------------------------------------------
    // INTERNAL

    /**
     * Retrieve the root-most node in the feed.
     *
     * @return stdClass
     */
    public function getRoot(): stdClass
    {
        return $this->root;
    }

    /**
     * Finds the common internal alias for a given XML node.
     *
     * @param string $nodeName The name of the XML node.
     *
     * @return string
     */
    protected function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'language':
                return 'lang';

            case 'pubDate':
            case 'publishedDate':
                return 'published';

            default:
                return $nodeName;
        }
    }

    /**
     * Get the correct handler for a whitelisted XML node name.
     *
     * @param string $nodeName The name of the XML node.
     *
     * @throws SimplePieException
     *
     * @return mixed
     */
    protected function getHandler(string $nodeName)
    {
        switch ($nodeName) {
            case 'id':
            case 'lang':
            case 'rights':
            case 'subtitle':
            case 'summary':
            case 'title':
                return $this->getScalarSingleValue($nodeName, $args[0]);

            case 'published':
            case 'updated':
                return (new DateParser(
                    $this->getScalarSingleValue($nodeName, $args[0])->getValue(),
                    $this->outputTimezone,
                    $this->createFromFormat
                ))->getDateTime();

            default:
                throw new SimplePieException(
                    \sprintf('%s is an unresolvable method.')
                );
        }
    }
}
