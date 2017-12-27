<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Type;

use SimplePie\Configuration;
use SimplePie\Mixin\LoggerTrait;

/**
 * A type model for an Entry element.
 *
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-Atom-1.0#412-the-atomentry-element
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#535-items
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-1.0#55-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-RSS-2.0#elements-of-item
 * @see https://github.com/simplepie/simplepie-ng/wiki/Spec%3A-JSON-Feed-v1#items
 */
class Entry extends AbstractType implements TypeInterface
{
    use LoggerTrait;

    /**
     * Constructs a new instance of this class.
     */
    public function __construct()
    {
        $this->logger = Configuration::getLogger();
    }
}
