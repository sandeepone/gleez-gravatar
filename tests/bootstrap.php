<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2014 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

require_once  dirname(realpath(dirname(__FILE__))).'/src/Gleez/Loader/Autoloader.php';

// turn on all errors
error_reporting(E_ALL);

// autoloader
$loader = new Gleez\Loader\Autoloader(array('autoregister' => true));

$loader->register(true);
