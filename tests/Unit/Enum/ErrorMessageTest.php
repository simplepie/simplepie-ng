<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit\Enum;

use SimplePie\Enum\ErrorMessage;
use SimplePie\Test\Unit\AbstractTestCase;

class ErrorMessageTest extends AbstractTestCase
{
    public function testIntrospectKeys(): void
    {
        $this->assertSame(ErrorMessage::introspectKeys(), [
            'LOGGER_NOT_PSR3',
            'MIDDLEWARE_NOT_HANDLERSTACK',
            'LIBXML_NOT_INTEGER',
        ]);
    }
}
