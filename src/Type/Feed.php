<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
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
     *
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    protected function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'categories':
                return 'category';

            case 'contributors':
                return 'contributor';

            case 'entries':
            case 'items':
                return 'entry';

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

    // phpcs:enable

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

            case 'author':
                return $this->getComplexSingleValue($nodeName, Person::class, $args[0]);

            case 'generator':
                return $this->getComplexSingleValue($nodeName, Generator::class, $args[0]);

            case 'icon':
            case 'logo':
                return $this->getComplexSingleValue($nodeName, Image::class, $args[0]);

            case 'category':
            case 'contributor':
            case 'entry':
            case 'link':
                return $this->getComplexMultipleValues($nodeName, $args[0]);

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }

    // phpcs:enable

    /**
     * Retrieves nodes that are simple scalars, and there is only one allowed value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return Node
     */
    protected function getScalarSingleValue(string $nodeName, ?string $namespaceAlias = null): Node
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->{$nodeName}[$alias])) {
            return $this->getRoot()->{$nodeName}[$alias];
        }

        return new Node();
    }

    /**
     * Retrieves nodes that are complex types, and there is only one allowed value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string      $className      The class name to instantiate when there is not a defined value.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return TypeInterface
     *
     * phpcs:disable Generic.Functions.OpeningFunctionBraceBsdAllman.BraceOnSameLine
     */
    protected function getComplexSingleValue(
        string $nodeName,
        string $className,
        ?string $namespaceAlias = null
    ): TypeInterface {
        // phpcs:enable

        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->{$nodeName}[$alias])) {
            return new $className($this->getRoot()->{$nodeName}[$alias]->getNode());
        }

        return new $className();
    }

    /**
     * Retrieves nodes that are complex types, and there may be are more than one value.
     *
     * @param string      $nodeName       The name of the tree node to retrieve. Available tree nodes can be viewed by
     *                                    looking at the response from `getRoot()`.
     * @param string|null $namespaceAlias The XML namespace alias to apply.
     *
     * @return iterable
     */
    protected function getComplexMultipleValues(string $nodeName, ?string $namespaceAlias = null): iterable
    {
        $alias = $namespaceAlias ?? $this->namespaceAlias;

        if (isset($this->getRoot()->{$nodeName}[$alias])) {
            return $this->getRoot()->{$nodeName}[$alias];
        }

        return [];
    }
}
