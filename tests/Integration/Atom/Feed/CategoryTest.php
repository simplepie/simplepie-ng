<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Node;
use SimplePie\Type\Category;
use Skyzyx\UtilityPack\Types;

class CategoryTest extends AbstractTestCase
{
    public function testCategory(): void
    {
        $category = $this->feed->getCategories()[0];

        $this->assertEquals(Category::class, Types::getClassOrType($category));
        $this->assertEquals('Clueless Recruiters', (string) $category);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($category->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($category->getTerm()));
        $this->assertEquals('Clueless Recruiters', (string) $category->getTerm());
        $this->assertEquals(Serialization::TEXT, $category->getTerm()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($category->getScheme()));
        $this->assertEquals('http://blog.ryanparman.com', (string) $category->getScheme());
        $this->assertEquals(Serialization::TEXT, $category->getScheme()->getSerialization());

        $this->assertEquals(Node::class, Types::getClassOrType($category->getLabel()));
        $this->assertEquals('', (string) $category->getLabel());
        $this->assertEquals(Serialization::TEXT, $category->getLabel()->getSerialization());
    }

    public function testCategories(): void
    {
        $categories = $this->feed->getCategories();

        foreach ($categories as $category) {
            $this->assertEquals(Category::class, Types::getClassOrType($category));
            $this->assertEquals(DOMElement::class, Types::getClassOrType($category->getNode()));
            $this->assertEquals(Node::class, Types::getClassOrType($category->getTerm()));
            $this->assertEquals(Serialization::TEXT, $category->getTerm()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($category->getScheme()));
            $this->assertEquals(Serialization::TEXT, $category->getScheme()->getSerialization());
            $this->assertEquals(Node::class, Types::getClassOrType($category->getLabel()));
            $this->assertEquals(Serialization::TEXT, $category->getLabel()->getSerialization());
        }
    }
}
