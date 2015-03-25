<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2015 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

namespace Gleez\Tests\Gravatar;

use Gleez\Gravatar\Gravatar;
use PHPUnit_Framework_TestCase;

/**
 * Gleez Gravatar Test
 *
 * @package  Gleez\Gravatar\UnitTest
 * @author   Gleez Team
 * @version  1.0.2
 */
class GravatarTest extends PHPUnit_Framework_TestCase
{
    protected $gravatar;

    public function setUp()
    {
        $this->gravatar = Gravatar::getInstance();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsIfInvalidSize()
    {
        $sizes = array(10000, 'xyz', NULL, TRUE, -10, 0.45);

        foreach ($sizes as $size) {
            $this->gravatar->setSize($size);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsIfInvalidRating()
    {
        $ratings = array(100, 'xyz', NULL, TRUE, '', 'rating');

        foreach ($ratings as $rating) {
            $this->gravatar->setRating($rating);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsIfInvalidDefaultImage()
    {
        $images = array(100, 'xyz', NULL, TRUE, '', 'john@doe.com', 'blankk');

        foreach ($images as $image) {
            $this->gravatar->setDefaultImage($image);
        }
    }

    public function testPossibleToUseImmediately()
    {
        $email     = 'me@klay.me';
        $expected1 = 'https://secure.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g';
        $expected2 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g';

        $this->gravatar->enableSecureURL();
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected1);

        $this->gravatar->disableSecureURL();
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected2);
    }

    public function testGenerateValidEmailHash()
    {
        $emails = array(
            'john@doe.com',
            'john.doe@SITE.com',
            'jOhN_DoE@site.com',
            'john_doe@domain.site.com',
            'john@doe-site.com',
            'j.o.h.n@d.o.e.com',
            'isposable.style.email.with+symbol@example.com'
        );

        foreach ($emails as $email) {
            $this->assertSame(md5(strtolower(trim($email))), $this->gravatar->getEmailHash($email));
            $this->assertSame(hash('md5', strtolower(trim($email))), $this->gravatar->getEmailHash($email));
        }
    }

    public function testSetRating()
    {
        $email     = 'me@klay.me';

        $rating1   = 'x';
        $rating2   = 'PG';

        $expected1 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=x';
        $expected2 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=pg';

        $this->gravatar->setRating($rating1);
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected1);

        $this->gravatar->setRating($rating2);
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected2);
    }

    public function testSetSize()
    {
        $email     = 'me@klay.me';

        $size1     = 180;
        $size2     = 0;
        $size3     = 2000;

        $expected1 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=180&r=g';
        $expected2 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=0&r=g';
        $expected3 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=2000&r=g';

        $this->gravatar->setRating('g');

        $this->gravatar->setSize($size1);
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected1);

        $this->gravatar->setSize($size2);
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected2);

        $this->gravatar->setSize($size3);
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected3);
    }

    public function testSetValidDefaultImage()
    {
        $email    = 'me@klay.me';

        $images   = array(
            '404',
            'mm',
            'identicon',
            'monsterid',
            'wavatar',
            'retro',
            'blank',
            'http://yoursitehere.com/path/to/image.png',
            false
        );

        $expected = array(
            '404',
            'mm',
            'identicon',
            'monsterid',
            'wavatar',
            'retro',
            'blank',
            'http://yoursitehere.com/path/to/image.png',
            false
        );

        $expectedURLs = array(
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=404',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=mm',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=identicon',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=monsterid',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=wavatar',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=retro',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=blank',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&d=http%3A%2F%2Fyoursitehere.com%2Fpath%2Fto%2Fimage.png',
            'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g'
        );

        foreach ($images as $key => $value) {
            $this->gravatar->setDefaultImage($value)
                           ->setSize(80);

            $this->assertEquals($this->gravatar->getDefaultImage(), $expected[$key]);
            $this->assertSame($this->gravatar->buildURL($email), $expectedURLs[$key]);
        }
    }

    public function testCanWorksWithForceMode()
    {
        $email     = 'me@klay.me';

        $expected1 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g&f=y';
        $expected2 = 'http://www.gravatar.com/avatar/58b4ea643d7161296f89910822d6e6a2?s=80&r=g';

        $this->gravatar->setSize(80)
                       ->setDefaultImage(false);

        $this->gravatar->enableForceDefault();
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected1);

        $this->gravatar->disableForceDefault();
        $url = $this->gravatar->buildURL($email);
        $this->assertSame($url, $expected2);
    }
}
