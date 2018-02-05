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
 * A type model for a Generator element.
 *
 * @method SimplePie\Type\Node getName() Returns the name of the Generator.
 * @method SimplePie\Type\Node getUri() Alias for `getUrl()`.
 * @method SimplePie\Type\Node getUrl() Returns the URL of the Generator.
 * @method SimplePie\Type\Node getVersion() Returns the version of the Generator.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#424-the-atomgenerator-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#optional-channel-elements
 */
class Generator extends AbstractType implements NodeInterface, TypeInterface, C\SetLoggerInterface
{
    use Tr\LoggerTrait;

    /**
     * The DOMNode element to parse.
     *
     * @var DOMNode
     */
    protected $node;

    /**
     * The generator name.
     *
     * @var string
     */
    protected $name;

    /**
     * The generator URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * The generator version.
     *
     * @var string
     */
    protected $version;

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
        return \trim(\sprintf('%s %s', $this->name, $this->version));
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
            case 'version':
                return $this->{$nodeName} ?? new Node();

            default:
                throw new SimplePieException(
                    $this->getUnresolvableMessage($nodeName)
                );
        }
    }
}
