<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2014 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

namespace Gleez\Gravatar;

// If already registered
if (interface_exists(__NAMESPACE__ . '\Gravatarable')) return;

/**
 * Gravatar interface
 *
 * Defining an interface for classes that use Gravatar service.
 *
 * @package  Gleez\Gravatar
 * @author   Gleez Team
 * @version  1.0.0
 */
interface Gravatarable
{
}
