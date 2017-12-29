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
 * A type model for an Image element.
 *
 * @method string getDescription() Returns the description of the Image.
 * @method string getHeight() Returns the height of the Image.
 * @method string getLink() Returns to where the Image should be linked.
 * @method string getTitle() Returns the title of the Image.
 * @method string getUri() Alias for `getUrl()`.
 * @method string getUrl() Returns the URL of the Image.
 * @method string getWidth() Returns the width of the Image.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#425-the-atomicon-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#428-the-atomlogo-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#534-image
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#image-sub-element-of-channel
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#top-level
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#itunesimage
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS#mediathumbnails
 */
class Image extends AbstractType implements NodeInterface, TypeInterface, C\SetLoggerInterface
{
    use Tr\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The image element's URL.
     *
     * @var string
     */
    protected $uri;

    /**
     * The image element's title.
     *
     * @var string
     */
    protected $title;

    /**
     * The image element's link.
     *
     * @var string
     */
    protected $link;

    /**
     * The image element's width, in pixels.
     *
     * @var int
     */
    protected $width;

    /**
     * The image element's height, in pixels.
     *
     * @var int
     */
    protected $height;

    /**
     * The image element's description.
     *
     * @var string
     */
    protected $description;

    /**
     * Constructs a new instance of this class.
     *
     * @param DOMNode|null    $node   The `DOMNode` element to parse.
     * @param LoggerInterface $logger The PSR-3 logger.
     */
    public function __construct(?DOMNode $node = null, LoggerInterface $logger = null)
    {
        if ($node) {
            $this->logger      = $logger ?? new NullLogger();
            $this->node        = $node;
            $this->uri         = new Node($this->node);
            $this->title       = null;
            $this->link        = null;
            $this->width       = null;
            $this->height      = null;
            $this->description = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->uri ?? '';
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
            case 'url':
                return 'uri';

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
            case 'uri':
            case 'title':
            case 'link':
            case 'width':
            case 'height':
            case 'description':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
