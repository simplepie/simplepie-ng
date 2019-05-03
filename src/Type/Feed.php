<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
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
 * The top-most element in a feed.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#411-the-atomfeed-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#53-channel
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#required-channel-elements
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#top-level
 *
 * @phpcs:disable Generic.Files.LineLength.MaxExceeded
 *
 * @method array getAuthors(string $namespaceAlias) Returns the Authors associated with this feed.
 * @method array getCategories(string $namespaceAlias) Returns the list of Categories/Tags/Topics associated with this feed.
 * @method array getContributors(string $namespaceAlias) Returns the list of Contributors associated with this feed.
 * @method SimplePie\Type\Node getCopyright(string $namespaceAlias) Alias for `getRights()`.
 * @method array getEntries(string $namespaceAlias) Returns the list of Entries/Items associated with this feed.
 * @method SimplePie\Type\Generator getGenerator(string $namespaceAlias) Returns the Generator associated with this feed.
 * @method SimplePie\Type\Node getGuid(string $namespaceAlias) Alias for `getId()`.
 * @method SimplePie\Type\Node getId(string $namespaceAlias) Returns the ID associated with this feed.
 * @method SimplePie\Type\Image getIcon(string $namespaceAlias) Returns the Icon associated with this feed.
 * @method array getItems(string $namespaceAlias) Alias for `getEntries()`.
 * @method SimplePie\Type\Node getLang(string $namespaceAlias) Alias for `getLanguage()`.
 * @method SimplePie\Type\Node getLanguage(string $namespaceAlias) Returns the language associated with this feed.
 * @method array getLinks(string $namespaceAlias, string $relFilter) Returns the list of Links associated with this feed.
 * @method SimplePie\Type\Image getLogo(string $namespaceAlias) Returns the Logo associated with this feed.
 * @method \DateTime getPubDate(string $namespaceAlias) Alias for `getPublished()`.
 * @method \DateTime getPublished(string $namespaceAlias) Returns the date that the feed was published.
 * @method SimplePie\Type\Node getRights(string $namespaceAlias) Returns the copyright information associated with this feed.
 * @method SimplePie\Type\Node getSubtitle(string $namespaceAlias) Returns the sub-title associated with this feed.
 * @method SimplePie\Type\Node getSummary(string $namespaceAlias) Returns the summary associated with this feed.
 * @method SimplePie\Type\Node getTitle(string $namespaceAlias) Returns the title associated with this feed.
 * @method \DateTime getUpdated(string $namespaceAlias) Returns the date that the feed was updated.
 *
 * @phpcs:enable
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
     * Finds the default namespace alias for the feed type.
     *
     * @return string
     */
    public function getDefaultNs(): string
    {
        return $this->namespaceAlias;
    }

    /**
     * Finds the common internal alias for a given method name.
     *
     * @param string $nodeName The name of the method being called.
     *
     * @return string
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'authors':
                return 'author';

            case 'categories':
                return 'category';

            case 'contributors':
                return 'contributor';

            case 'copyright':
                return 'rights';

            case 'entries':
            case 'items':
                return 'entry';

            case 'guid':
                return 'id';

            case 'language':
                return 'lang';

            case 'links':
                return 'link';

            case 'pubDate':
                return 'published';

            default:
                return $nodeName;
        }
    }

    // @phpcs:enable

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
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getHandler(string $nodeName, array $args = [])
    {
        switch ($nodeName) {
            case 'base':
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

            case 'generator':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Generator::class, $args[0] ?? null);

            case 'icon':
            case 'logo':
                return $this->getComplexSingleValue($this->getRoot(), $nodeName, Image::class, $args[0] ?? null);

            case 'author':
            case 'category':
            case 'contributor':
            case 'entry':
                return $this->getComplexMultipleValues($this->getRoot(), $nodeName, $args[0] ?? null);

            case 'link':
                $links = $this->getComplexMultipleValues($this->getRoot(), $nodeName, $args[0] ?? null);

                if (isset($args[1])) {
                    $relFilter = $args[1];

                    return \array_values(
                        \array_filter($links, static function (Link $link) use ($relFilter) {
                            return $relFilter === $link->getRel()->getValue();
                        })
                    );
                }

                return $links;

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }

    // @phpcs:enable
}
