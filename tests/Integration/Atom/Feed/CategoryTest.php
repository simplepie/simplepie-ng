<?php
/**
 * Copyright (c) 2017–2019 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2019 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Exception\SimplePieException;
use SimplePie\HandlerStack;
use SimplePie\Middleware\Xml\Atom;
use SimplePie\SimplePie;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Category;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class CategoryTest extends AbstractTestCase
{
    // override
    public function getSimplePie(): SimplePie
    {
        return (new SimplePie())
            ->setMiddlewareStack(
                (new HandlerStack())
                    ->append(
                        (new Atom())
                            ->setCaseInsensitive()
                    )
            );
    }

    public function testCategory(): void
    {
        $category = $this->feed->getCategories()[0];

        static::assertEquals(Category::class, Types::getClassOrType($category));
        static::assertEquals('Clueless Recruiters', (string) $category);

        static::assertEquals(DOMElement::class, Types::getClassOrType($category->getNode()));

        static::assertEquals(Node::class, Types::getClassOrType($category->getTerm()));
        static::assertEquals('Clueless Recruiters', (string) $category->getTerm());
        static::assertEquals(Serialization::TEXT, $category->getTerm()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($category->getScheme()));
        static::assertEquals('http://blog.ryanparman.com', (string) $category->getScheme());
        static::assertEquals(Serialization::TEXT, $category->getScheme()->getSerialization());

        static::assertEquals(Node::class, Types::getClassOrType($category->getLabel()));
        static::assertEquals('', (string) $category->getLabel());
        static::assertEquals(Serialization::TEXT, $category->getLabel()->getSerialization());
    }

    public function testCategories(): void
    {
        $categories = $this->feed->getCategories();

        foreach ($categories as $category) {
            static::assertEquals(Category::class, Types::getClassOrType($category));
            static::assertEquals(DOMElement::class, Types::getClassOrType($category->getNode()));
            static::assertEquals(Node::class, Types::getClassOrType($category->getTerm()));
            static::assertEquals(Serialization::TEXT, $category->getTerm()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($category->getScheme()));
            static::assertEquals(Serialization::TEXT, $category->getScheme()->getSerialization());
            static::assertEquals(Node::class, Types::getClassOrType($category->getLabel()));
            static::assertEquals(Serialization::TEXT, $category->getLabel()->getSerialization());
        }
    }

    public function testCategoryFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $category = $this->feed->getCategories()[0];
        $category->getDoesntExist();
    }
}
