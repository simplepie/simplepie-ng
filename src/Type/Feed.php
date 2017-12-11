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
use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Parser\Date as DateParser;
use stdClass;

/**
 * Represents the top-level of a feed.
 */
class Feed extends AbstractType implements TypeInterface, C\SetLoggerInterface
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
     * @var string
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
        $this->logger         = new NullLogger();
        $this->namespaceAlias = $namespaceAlias;
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

    public function getAuthor(?string $namespaceAlias = null): Person
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->author[$alias])) {
            return $this->getRoot()->author[$alias];
        }

        return new Person();
    }

    //--------------------------------------------------------------------------
    // MULTIPLE COMPLEX VALUES

    public function getContributors(?string $namespaceAlias = null): iterable
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->contributor[$alias])) {
            return $this->getRoot()->contributor[$alias];
        }

        return [];
    }

    public function getLinks(?string $namespaceAlias = null): iterable
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->link[$alias])) {
            return $this->getRoot()->link[$alias];
        }

        return [];
    }

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
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
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
     * Get the correct handler for a whitelisted method name.
     *
     * @param string $nodeName The name of the method being called.
     * @param array  $args     Any arguments passed into that method.
     *
     * @throws SimplePieException
     *
     * @return mixed
     */
    protected function getHandler(string $nodeName, array $args)
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
