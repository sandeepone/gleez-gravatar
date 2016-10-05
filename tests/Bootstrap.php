<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2015 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

namespace Gleez\Tests\Gravatar;

// turn on all errors
error_reporting(E_ALL | E_STRICT);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
    public static function init()
    {
        self::initAutoloader();
    }

    protected static function initAutoloader()
    {
        $vendorPath = dirname(dirname(__FILE__)) . '/vendor';

        if (!is_file($vendorPath . '/autoload.php')) {
            throw new \RuntimeException(
                'Unable to locate autoloader. Run `composer install` from the project root directory.'
            );
        }

        include $vendorPath . '/autoload.php';
    }
}

Bootstrap::init();
