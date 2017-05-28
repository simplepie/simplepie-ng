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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SimplePie\Enum\ErrorMessage;
use SimplePie\Exception\ConfigurationException;
use SimplePie\Mixin\ContainerTrait;
use SimplePie\Mixin\LoggerTrait;
use Skyzyx\UtilityPack\Types;

class Configure
{
    use ContainerTrait;
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface $container A PSR-11 dependency injection container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // Run validations
        $this->validateLogger();
    }

    /**
     * Validates the user's configuration for the PSR-3 logger.
     *
     * A valid PSR-3 logger set by the user will be utilized. If there is no
     * logger set, the default value will be `NullLogger`. An invalid setting
     * will throw an exception.
     *
     * @throws ConfigurationException
     */
    protected function validateLogger(): void
    {
        // The PSR-3 logger
        if ($this->container->has('__sp__.logger')) {
            if ($this->container['__sp__.logger'] instanceof LoggerInterface) {
                $this->logger = $this->container['__sp__.logger'];
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LOGGER_NOT_PSR3,
                        Types::getClassOrType($this->container['__sp__.logger'])
                    )
                );
            }
        } else {
            $this->logger = new NullLogger();
        }
    }
}
