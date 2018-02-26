<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Integration\Atom\Feed;

use DOMElement;
use SimplePie\Enum\Serialization;
use SimplePie\Exception\SimplePieException;
use SimplePie\Test\Integration\AbstractTestCase;
use SimplePie\Type\Image;
use SimplePie\Type\Node;
use Skyzyx\UtilityPack\Types;

class ImageTest extends AbstractTestCase
{
    public function testIcon(): void
    {
        $image = $this->feed->getIcon();

        $this->assertEquals(Image::class, Types::getClassOrType($image));
        $this->assertEquals('http://www.facebook.com/favicon.ico', (string) $image);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($image->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($image->getUrl()));
        $this->assertEquals('http://www.facebook.com/favicon.ico', (string) $image->getUrl());
        $this->assertEquals(Serialization::TEXT, $image->getUrl()->getSerialization());

        $this->assertEquals('string', Types::getClassOrType($image->getUrl()->getValue()));
        $this->assertEquals('http://www.facebook.com/favicon.ico', $image->getUrl()->getValue());
    }

    public function testLogo(): void
    {
        $url   = 'http://profile.ak.fbcdn.net/hprofile-ak-snc4/41604_92024478835_8378438_n.jpg';
        $image = $this->feed->getLogo();

        $this->assertEquals(Image::class, Types::getClassOrType($image));
        $this->assertEquals($url, (string) $image);

        $this->assertEquals(DOMElement::class, Types::getClassOrType($image->getNode()));

        $this->assertEquals(Node::class, Types::getClassOrType($image->getUrl()));
        $this->assertEquals($url, (string) $image->getUrl());
        $this->assertEquals(Serialization::TEXT, $image->getUrl()->getSerialization());

        $this->assertEquals('string', Types::getClassOrType($image->getUrl()->getValue()));
        $this->assertEquals($url, $image->getUrl()->getValue());
    }

    public function testImageAliases(): void
    {
        $url   = 'http://profile.ak.fbcdn.net/hprofile-ak-snc4/41604_92024478835_8378438_n.jpg';
        $image = $this->feed->getLogo();

        $this->assertEquals($url, (string) $image->getUrl());
        $this->assertEquals($url, (string) $image->getUri());
    }

    public function testImageFail(): void
    {
        $this->expectException(SimplePieException::class);
        $this->expectExceptionMessage('getDoesntExist is an unresolvable method.');

        $image = $this->feed->getLogo();
        $image->getDoesntExist();
    }
}
