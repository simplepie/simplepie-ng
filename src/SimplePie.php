<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Exception\ConfigurationException;
use SimplePie\Mixin\LibxmlTrait;
use SimplePie\Mixin\LoggerTrait;
use SimplePie\Mixin\MiddlewareStackTrait;
use SimplePie\Parser\Xml as XmlParser;
use Skyzyx\UtilityPack\Types;

define('SIMPLEPIE_ROOT', __DIR__);

class SimplePie
{
    use LibxmlTrait;
    use LoggerTrait;
    use MiddlewareStackTrait;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct(array $options = [])
    {
        // Run validations
        $this->validateLogger($options);
        $this->validateLibxml($options);
        $this->validateMiddlewareStack($options);

        $this->logger->info(sprintf('`%s` has completed instantiation.', __CLASS__));
    }

    //---------------------------------------------------------------------------

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
        $parser = new XmlParser($this->logger, $this->middleware, $stream, $handleHtmlEntitiesInXml, $this->libxml);

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
     * @param array $options The options which were passed into the constructor.
     *
     * @throws ConfigurationException
     */
    protected function validateLogger(array $options = []): void
    {
        if (isset($options['logger'])) {
            if ($options['logger'] instanceof LoggerInterface) {
                $this->logger = $options['logger'];
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LOGGER_NOT_PSR3,
                        Types::getClassOrType($options['logger'])
                    )
                );
            }
        } else {
            $this->logger = new NullLogger();
        }

        // What are we logging with?
        $this->logger->debug(sprintf(
            'Logger configured to use `%s`.',
            Types::getClassOrType($this->logger)
        ));
    }

    /**
     * Validates the user's configuration for `LIBXML_*` XML parsing parameters. If no valid values are set, the
     * default value will be `LIBXML_NOCDATA`.
     *
     * @param array $options The options which were passed into the constructor.
     *
     * @throws ConfigurationException
     */
    protected function validateLibxml(array $options = []): void
    {
        if (isset($options['libxml'])) {
            if (is_int($options['libxml'])) {
                $this->libxml = $options['libxml'];
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LIBXML_NOT_INTEGER,
                        Types::getClassOrType($options['libxml'])
                    )
                );
            }
        } else {
            $this->libxml = 0
                | LIBXML_BIGLINES
                | LIBXML_COMPACT
                | LIBXML_HTML_NODEFDTD
                | LIBXML_HTML_NOIMPLIED // Required, or things crash.
                | LIBXML_NOBLANKS
                // | LIBXML_NOCDATA // Do not merge into text nodes.
                | LIBXML_NOENT
                | LIBXML_NOXMLDECL
                | LIBXML_NSCLEAN
                | LIBXML_PARSEHUGE
            ;
        }

        // What are we logging with?
        $this->logger->debug(sprintf(
            'Libxml configuration has a bitwise value of `%s`.%s',
            $this->libxml,
            ($this->libxml === 4808966)
                ? ' (This is the default configuration.)'
                : ''
        ));
    }

    /**
     * Validates the user's configuration for the middleware handler stack.
     *
     * A valid middleware handler stack by the user will be utilized. If there
     * is no handler stack set, the default value will be `HandlerStack`. An
     * invalid setting will throw an exception.
     *
     * @param array $options The options which were passed into the constructor.
     *
     * @throws ConfigurationException
     */
    protected function validateMiddlewareStack(array $options = []): void
    {
        if (isset($options['middleware'])) {
            if ($options['middleware'] instanceof HandlerStackInterface) {
                $this->middleware = $options['middleware'];
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::MIDDLEWARE_NOT_HANDLERSTACK,
                        Types::getClassOrType($options['logger'])
                    )
                );
            }
        } else {
            $this->middleware = new HandlerStack($this->logger);
        }
    }
}
