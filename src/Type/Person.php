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
use SimplePie\Mixin as T;

/**
 * A type model for a Person element.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#32-person-constructs
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#author-sub-element-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#top-level
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#itunesauthor
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-iTunes-Podcast-RSS#itunesowner
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Media-RSS#mediacredit
 */
class Person extends AbstractType implements NodeInterface, TypeInterface, C\SetLoggerInterface
{
    use T\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The person's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The person's URL.
     *
     * @var string
     */
    protected $uri;

    /**
     * The person's email address.
     *
     * @var string
     */
    protected $email;

    /**
     * The person's avatar.
     *
     * @var string
     */
    protected $avatar;

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
            $this->name   = new Node($this->node);

            foreach ($this->node->childNodes as $child) {
                $this->{$child->tagName} = new Node($child);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        if (null !== $this->name && (null !== $this->uri || null !== $this->email)
        ) {
            return \trim(\sprintf('%s <%s>', (string) $this->name, (string) ($this->uri ?? $this->email)));
        }

        return \trim(
            (string) $this->name
            ?? (string) $this->uri
            ?? (string) $this->email
            ?? 'Unknown'
        );
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
    public function getHandler(string $nodeName, array $args = []): Node
    {
        switch ($nodeName) {
            case 'name':
            case 'uri':
            case 'email':
            case 'avatar':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
