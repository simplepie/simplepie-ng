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
 * A type model for a Person element.
 *
 * @method SimplePie\Type\Node getAvatar() Returns the avatar URL of the Person.
 * @method SimplePie\Type\Node getEmail() Returns the email address of the Person.
 * @method SimplePie\Type\Node getName() Returns the name of the Person.
 * @method SimplePie\Type\Node getUri() Alias for `getUrl()`.
 * @method SimplePie\Type\Node getUrl() Returns the URL of the Person.
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
    use Tr\LoggerTrait;

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

            // Map to url if uri doesn't exist.
            $this->uri = $this->uri ?? $this->url ?? null;
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
        // Shut up, linter.
        $args;

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
