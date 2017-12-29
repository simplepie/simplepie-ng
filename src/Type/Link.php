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

/**
 * A type model for a Link element.
 *
 * @method string getHref() Alias for `getUrl()`.
 * @method string getHrefLang() Alias for `getLanguage()`.
 * @method string getLang() Alias for `getLanguage()`.
 * @method string getLanguage() Returns the language of the Link.
 * @method string getLength() Returns the length of the Link, in bytes.
 * @method string getMediaType() Returns the media type (née mime type) of the Link.
 * @method string getRel() Alias for `getRelationship()`.
 * @method string getRelationship() Returns the relationship of the Link.
 * @method string getTitle() Returns the title of the Link.
 * @method string getType() Alias for `getMediaType()`.
 * @method string getUri() Alias for `getUrl()`.
 * @method string getUrl() Returns the URL of the Link.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#427-the-atomlink-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#532-link
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#required-channel-elements
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#top-level
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#link
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#itunesnew-feed-url
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS#mediabacklinks
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS#mediapeerlink
 */
class Link extends AbstractType implements NodeInterface, TypeInterface, C\SetLoggerInterface
{
    use Tr\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The link's remote location.
     *
     * @var string
     */
    protected $href;

    /**
     * The link's relationship to the current document.
     *
     * @var string
     */
    protected $rel;

    /**
     * The link's media type.
     *
     * @var string
     */
    protected $type;

    /**
     * The language of the link's remote location.
     *
     * @var string
     */
    protected $hreflang;

    /**
     * The link's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The link's length, in bytes (e.g., if it is a large file or direct download).
     *
     * @var int
     */
    protected $length;

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode|null    $node   The `DOMNode` element to parse.
     * @param LoggerInterface $logger The PSR-3 logger.
     */
    public function __construct(?DOMNode $node = null, LoggerInterface $logger = null)
    {
        if ($node) {
            $this->logger = $logger ?? new NullLogger();
            $this->node   = $node;

            foreach ($this->node->attributes as $attribute) {
                $this->{$attribute->name} = new Node($attribute);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->href ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(): ?DOMNode
    {
        return $this->node;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(string $nodeName): string
    {
        switch ($nodeName) {
            case 'uri':
            case 'url':
                return 'href';

            case 'relationship':
                return 'rel';

            case 'mediaType':
                return 'type';

            case 'lang':
            case 'language':
                return 'hreflang';

            default:
                return $nodeName;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(string $nodeName): Node
    {
        switch ($nodeName) {
            case 'href':
            case 'hreflang':
            case 'length':
            case 'rel':
            case 'title':
            case 'type':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
