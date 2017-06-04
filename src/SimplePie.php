<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Exception\ConfigurationException;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Parser\Xml as XmlParser;
use Skyzyx\UtilityPack\Types;

class SimplePie
{
    use LoggerTrait;

    /**
     * The PSR-11 dependency injection container.
     *
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(ContainerInterface $container)
    {
        // Run validations
        static::validateLogger($container);
        static::validateLibxml($container);
        static::validateDomExtensions($container);

        static::$container = $container;

        static::getLogger()->debug(sprintf('`%s` has completed instantiation.', __CLASS__));
    }

    /**
     * Gets a PSR-11 dependency injection container.
     *
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return static::$container;
    }

    /**
     * Parses content which is known to be valid XML and is encoded as UTF-8.
     *
     * @param StreamInterface $stream                  A PSR-7 `StreamInterface` which is typically returned by the
     *                                                 `getBody()` method of a `ResponseInterface` class.
     * @param bool            $handleHtmlEntitiesInXml Whether or not SimplePie should pre-parse the XML as HTML to
     *                                                 resolve the entities. A value of `true` means that SimplePie
     *                                                 should inject the entity definitions. A value of `false` means
     *                                                 that SimplePie should NOT inject the entity definitions. The
     *                                                 default value is `false`.
     *
     * @return XmlParser
     */
    public function parseXml(StreamInterface $stream, bool $handleHtmlEntitiesInXml = false): XmlParser
    {
        $parser = new XmlParser($stream, $handleHtmlEntitiesInXml);

        return $parser;
    }

    /**
     * Parses content which is known to be valid JSON and is encoded as UTF-8.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by the
     *                                `getBody()` method of a `ResponseInterface` class.
     *
     * @return JsonParser
     */
    public function parseJson(StreamInterface $stream)
    {
        return $stream->getContents();
    }

    //---------------------------------------------------------------------------

    /**
     * Validates the user's configuration for the PSR-3 logger.
     *
     * A valid PSR-3 logger set by the user will be utilized. If there is no
     * logger set, the default value will be `NullLogger`. An invalid setting
     * will throw an exception.
     *
     * @throws ConfigurationException
     */
    protected static function validateLogger(ContainerInterface $container): void
    {
        // The PSR-3 logger
        if ($container->has('_.logger')) {
            if (!$container['_.logger'] instanceof LoggerInterface) {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LOGGER_NOT_PSR3,
                        Types::getClassOrType($container['_.logger'])
                    )
                );
            }
        } else {
            $container['_.logger'] = new NullLogger();
        }

        // What are we logging with?
        $container['_.logger']->info(sprintf(
            'Logger configured to use `%s`.',
            Types::getClassOrType($container['_.logger'])
        ));
    }

    /**
     * Validates the user's configuration for `LIBXML_*` XML parsing parameters.
     *
     * A valid PSR-3 logger set by the user will be utilized. If there is no
     * logger set, the default value will be `NullLogger`. An invalid setting
     * will throw an exception.
     *
     * @throws ConfigurationException
     */
    protected static function validateLibxml(ContainerInterface $container): void
    {
        // The PSR-3 logger
        if (!$container->has('_.dom.libxml')) {
            $container['_.dom.libxml'] = 0
                | LIBXML_BIGLINES
                | LIBXML_COMPACT
                | LIBXML_HTML_NODEFDTD
                | LIBXML_HTML_NOIMPLIED
                | LIBXML_NOBLANKS
                | LIBXML_NOCDATA
                | LIBXML_NOENT
                | LIBXML_NSCLEAN
                | LIBXML_PARSEHUGE;
        }

        // What are we logging with?
        $container['_.logger']->debug(sprintf(
            'Libxml configuration has a bitwise value of `%s`.%s',
            $container['_.dom.libxml'],
            ($container['_.dom.libxml'] === 4808966)
                ? ' (This is the default configuration.)'
                : ''
        ));
    }

    /**
     * Pre-processes any DOM-extending classes you have defined in userland. These are applied to
     * the top-level `DOMDocument` object using `registerNodeClass()`.
     *
     * This only works with classes which extend from `DOMNode`.
     *
     * @see http://php.net/manual/en/domdocument.registernodeclass.php
     * @see https://bugs.php.net/48352
     */
    protected static function validateDomExtensions(ContainerInterface $container): void
    {
        $map        = [];
        $domClasses = [
            'Attr',
            'CdataSection',
            'CharacterData',
            'Comment',
            'Document',
            'DocumentFragment',
            'DocumentType',
            'Element',
            'Entity',
            'EntityReference',
            'Node',
            'Notation',
            'ProcessingInstruction',
            'Text',
        ];

        foreach ($domClasses as $domClass) {
            if ($container->has(sprintf('_.dom.extend.%s', $domClass))) {
                $map[$domClass] = $container[sprintf('_.dom.extend.%s', $domClass)];
            }
        }

        $container['_.dom.extend._matches'] = $map;

        if (!empty($map)) {
            $container['_.logger']->debug('DOMDocument is configured to use extended classes.', $map);
        }
    }
}
