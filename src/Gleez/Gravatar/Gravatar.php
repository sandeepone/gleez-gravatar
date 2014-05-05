<?php
/**
 * Gleez CMS (http://gleezcms.org)
 *
 * @link      https://github.com/gleez/cms Canonical source repository
 * @copyright Copyright (c) 2011-2014 Gleez Technologies
 * @license   http://gleezcms.org/license Gleez CMS License
 */

namespace Gleez\Gravatar;

use InvalidArgumentException;

/**
 * The Gleez Gravatar component provides an easy way to retrieve a user's profile image
 * from Gravatar site based on a given email address.
 *
 * If the email address cannot be matched with a Gravatar account, an alternative will
 * be returned based on the Gravatar::$defaultImage. setting. Users with gravatars can
 * have a default image if you want to.
 *
 * @package  Gleez\Gravatar
 * @author   Gleez Team
 * @version  1.0.0
 */
class Gravatar implements Gravatarable
{
    /**
     * The gravatar service URL
     * @type string
     */
    const HTTP_URL  = 'http://www.gravatar.com/avatar/';

    /**
     * The secure gravatar service URL
     * @type string
     */
    const HTTPS_URL = 'https://secure.gravatar.com/avatar/';

    /**
     * Minimum size of requested avatar
     * @type int
     */
    const MIN_AVATAR_SIZE = 0;

    /**
     * Maximum size of requested avatar
     * @type int
     */
    const MAX_AVATAR_SIZE = 2048;

    /**
     * @var Gleez\Gravatar\Gravatar
     */
    protected static $instance;

    /**
     * The default image.
     *
     * Possible values:
     * - String of the gravatar-recognized default image "type" to use
     * - URL
     * - false if using the default gravatar image
     *
     * @var mixed
     */
    private $defaultImage = false;

    /**
     * Gravatar defaults
     * @var array
     */
    private $validDafaults = array(
        '404'       => true,
        'mm'        => true,
        'identicon' => true,
        'monsterid' => true,
        'wavatar'   => true,
        'retro'     => true,
        'blank'     => true
    );

    /**
     * Gravatar rating
     * @var array
     */
    private $validRatings = array(
        'g'  => true,
        'pg' => true,
        'r'  => true,
        'x'  => true
    );

    /**
     * The size to use for avatars
     * @var int
     */
    private $size = 80;

    /**
     * The maximum rating to allow for the avatar
     * @var string
     */
    private $rating = 'g';

    /**
     * Should we use the secure (HTTPS) URL base?
     * @var bool
     */
    private $secureURL = false;

    /**
     * The default image shall be shown even if user that has an gravatar profile.
     * @var bool
     */
    private $forceDefault = false;

    /**
     * The Class Constructor
     *
     * Private constructor because this is a singleton class.
     */
    private function __construct()
    {
    }

    /**
     * Create and return and return Gleez\Gravatar\Gravatar instance
     *
     * @return Gleez\Gravatar\Gravatar
     */
    public static function getInstance()
    {
        if (!static::$instance instanceof Gravatarable) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /**
     * Set the default image to use for avatars
     *
     * Possible $image formats:
     * - a string specifying a recognized gravatar "default"
     * - a string containing a valid image URL
     * - boolean false for the gravatar default
     *
     * @param mixed $image The default image to use
     * @return Gleez\Gravatar\Gravatar
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultImage($image)
    {
        if (false === $image) {
            $this->defaultImage = $image;

            return $this;
        }

        $default = strtolower(trim($image));

        if (!isset($this->validDafaults[$default])) {
            if(!filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
                throw new InvalidArgumentException(
                    'The default image specified is not a recognized gravatar "default" and is not a valid URL'
                );
            } else {
                $this->defaultImage = $image;
            }
        } else {
            $this->defaultImage = $default;
        }

        return $this;
    }

    /**
     * Get the current default image
     *
     * @return string  if one is set
     * @return bool    false if no default image set
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }

    /**
     * Set the avatar size to use
     *
     * By default, images from Gravatar.com will be returned as 80x80px
     *
     * @param int $size The avatar size to use
     * @return Gleez\Gravatar\Gravatar
     *
     * @throws \InvalidArgumentException
     */
    public function setSize($size)
    {
        $options = array(
            'options' => array(
                'min_range' => static::MIN_AVATAR_SIZE,
                'max_range' => static::MAX_AVATAR_SIZE
        ));

        if(false === filter_var($size, FILTER_VALIDATE_INT, $options)) {
            throw new InvalidArgumentException(
                'Avatar size must be an integer within '.static::MIN_AVATAR_SIZE.' pixels and '.static::MAX_AVATAR_SIZE.' pixels'
            );
        }

        $this->size = $size;

        return $this;
    }

    /**
     * Get the currently set avatar size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the maximum allowed rating for avatars
     *
     * @param string $rating The maximum rating to use for avatars ('g', 'pg', 'r', 'x')
     * @return Gleez\Gravatar\Gravatar
     *
     * @throws \InvalidArgumentException
     */
    public function setRating($rating)
    {
        $rating = strtolower(trim($rating));
        $allowed = implode(', ', array_keys($this->validRatings));

        if (!isset($this->validRatings[$rating])) {
            throw new InvalidArgumentException(
                sprintf('Invalid rating "%s" specified. Available for use only: %s.', $rating, $allowed)
            );
        }

        $this->rating = $rating;

        return $this;
    }

    /**
     * Get the current maximum allowed rating for avatars
     *
     * The string representing the current maximum allowed rating ('g', 'pg', 'r', 'x').
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Check if we are using the secure protocol for the image URLs
     *
     * @return bool
     */
    public function useSecureURL()
    {
        return $this->secureURL;
    }

    /**
     * Enable the use of the secure protocol for image URLs
     *
     * @return Gleez\Gravatar\Gravatar
     */
    public function enableSecureURL()
    {
        $this->secureURL = true;

        return $this;
    }

    /**
     * Disable the use of the secure protocol for image URLs
     *
     * @return Gleez\Gravatar\Gravatar
     */
    public function disableSecureURL()
    {
        $this->secureURL = false;

        return $this;
    }

    /**
     * Get the email hash to use
     *
     * @param string $email The email to get the hash for
     * @return string
     */
    public function getEmailHash($email)
    {
        return md5(strtolower(trim($email)));
    }

    /**
     * Forces Gravatar to display default image
     *
     * @return Gleez\Gravatar\Gravatar
     */
    public function enableForceDefault()
    {
        $this->forceDefault = true;

        return $this;
    }

    /**
     * Disable forces default image
     *
     * @return Gleez\Gravatar\Gravatar
     */
    public function disableForceDefault()
    {
        $this->forceDefault = false;

        return $this;
    }

    /**
     * Check if need to force the default image to always load
     *
     * @return bool
     */
    public function useForceDefault()
    {
        return $this->forceDefault;
    }

    /**
     * Build the Gravatar URL based on the configuration and provided email address
     *
     * @param string $email The email to get the gravatar for
     * @return string
     */
    public function buildURL($email)
    {
        $url = static::HTTP_URL;

        if ($this->useSecureURL()) {
            $url = static::HTTPS_URL;
        }

        $url .= $this->getEmailHash($email);

        $query = array(
            's' => $this->getSize(),
            'r' => $this->getRating()
        );

        if ($this->getDefaultImage()) {
            $query = array_merge($query, array('d' => $this->getDefaultImage()));
        }

        if ($this->useForceDefault()) {
            $query = array_merge($query, array('f' => 'y'));
        }

        $url .= '?'.http_build_query($query, '', '&');

        return $url;
    }
}
