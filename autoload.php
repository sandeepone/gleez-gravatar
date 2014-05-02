<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2014 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

spl_autoload_register(function ($class) {
    
    // what namespace prefix should be recognized?
    $prefix = 'Gleez\Gravatar\\';
    
    // does the requested class match the namespace prefix?
    $prefix_len = strlen($prefix);
    if (substr($class, 0, $prefix_len) !== $prefix) {
        return;
    }
    
    // strip the prefix off the class
    $class = substr($class, $prefix_len);
    
    // a partial filename
    $part = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    
    // directories where we can find classes
    $dirs = array(
        __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Gleez' . DIRECTORY_SEPARATOR . 'Gravatar',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Gleez' . DIRECTORY_SEPARATOR . 'Gravatar',
    );
    
    // go through the directories to find classes
    foreach ($dirs as $dir) {
        $file = $dir . DIRECTORY_SEPARATOR . $part;
        if (is_readable($file)) {
            require $file;
            return;
        }
    }
});
