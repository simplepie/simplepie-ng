<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Exception;

use Exception;
use SimplePie\Mixin\LoggerTrait;
use Throwable;

class SimplePieException extends Exception
{
    use LoggerTrait;

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->getLogger()->debug(sprintf('Exception `%s` is being thrown right now.', get_called_class()));
        parent::__construct($message, $code, $previous);
    }
}
