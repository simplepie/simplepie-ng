<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use Psr\Log\NullLogger;
use SimplePie\Configuration as C;
use SimplePie\Exception\SimplePieException;
use SimplePie\Mixin as Tr;
use SimplePie\Parser\Date as DateParser;
use stdClass;

/**
 * Represents the top-level of a feed.
 */
class Feed extends AbstractType implements BranchInterface, C\SetLoggerInterface
{
    use Tr\DateTrait;
    use Tr\DeepTypeTrait;
    use Tr\LoggerTrait;
    use Tr\RootTrait;

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
     * @param string $namespaceAlias [description]
     */
    public function __construct(string $namespaceAlias)
    {
        $this->root           = new stdClass();
        $this->logger         = new NullLogger();
        $this->namespaceAlias = $namespaceAlias;
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
    public function getAlias(string $nodeName): string
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
     * @param array  $args     Any arguments passed into that method. The default value is an empty array.
     *
     * @throws SimplePieException
     *
     * @return mixed Either `TypeInterface` or `TypeInterface[]`.
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
                return $this->getScalarSingleValue($this->getRoot(), $nodeName, $args[0] ?? null);

            case 'published':
            case 'updated':
                return (new DateParser(
                    $this->getScalarSingleValue($this->getRoot(), $nodeName, $args[0] ?? null)->getValue(),
                    $this->outputTimezone,
                    $this->createFromFormat
                ))->getDateTime();

            case 'author':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Person::class, $args[0] ?? null);

            case 'generator':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Generator::class, $args[0] ?? null);

            case 'icon':
            case 'logo':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Image::class, $args[0] ?? null);

            case 'category':
            case 'contributor':
            case 'entry':
            case 'link':
                return $this->getComplexMultipleValues($this->getRoot(), $nodeName, $args[0] ?? null);

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }

    // phpcs:enable
}
