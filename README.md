# Gleez Gravatar

[![Build Status](https://travis-ci.org/sergeyklay/gleez-gravatar.svg?branch=master)](https://travis-ci.org/sergeyklay/gleez-gravatar)
[![Total Downloads](http://img.shields.io/packagist/dm/gleez/gravatar.svg)](https://packagist.org/packages/gleez/gravatar)
[![Latest Version](http://img.shields.io/github/tag/sergeyklay/gleez-gravatar.svg)](https://github.com/sergeyklay/gleez-gravatar/releases)
[![Dependency Status](https://www.versioneye.com/user/projects/5363c11efe0d07194f000001/badge.png)](https://www.versioneye.com/user/projects/5363c11efe0d07194f000001)


## What Is Gleez Gravatar?

[Gravatar's](http://gravatar.com/) are universal avatars available to all web sites and services. Users must register their email addresses with Gravatar before their avatars will be usable in your project.

The Gleez Gravatar component provides an easy way to retrieve a user's profile image from [Gravatar](http://gravatar.com/) based on a given email address. If the email address cannot be matched with a Gravatar account, an alternative will be returned based on the `Gravatar::$defaultImage` setting.

Users with gravatars can have a default image if you want to.


## Requirements

### Tested PHP versions

* 5.3.x
* 5.4.x
* 5.5.x
* 5.6.x
* hhvm

### Additional requirements

* [Gleez Autoloader](https://github.com/sergeyklay/gleez-autoloader) 1.0.* or higher (for testing)
* PHPUnit 4.0.0 or higher (for testing)


## Installation

__Git way__

```bash
$ git clone https://github.com/sergeyklay/gleez-gravatar
```

__Composer way__

Download and install composer from [http://www.getcomposer.org](http://www.getcomposer.org):

```bash
$ curl -sS https://getcomposer.org/installer | php
```

Add the following to your project `composer.json` file:

```json
{
    "require": {
        "gleez/gravatar": "dev-master"
    }
}
```

When you're done just run


```bash
$ php composer.phar install
```

and the package is ready to be used. Another way, if you already have composer:

```bash
$ composer require --prefer-source gleez/gravatar:dev-master
```

__Direct zipball download__

https://github.com/sergeyklay/gleez-gravatar/archive/master.zip


## Getting Started

```php
// Some preparation with your class autoloader
// ...

// Get Gravatar instance
$gravatar = Gleez\Gravatar\Gravatar::getInstance();

// Setting default image, maximum size and maximum allowed Gravatar rating
$gravatar->setDefaultImage('retro')
         ->setSize(220)
         ->setRating('pg');

// Build the Gravatar URL based on the configuration and provided email address
$avatar = $gravatar->buildURL('john@doe.com');

```

## Configuration

### Gravatar size

Gravatar allows avatar images ranging from 0px to 2048px in size. With using 0px size, images from Gravatar will be returned as 80x80px.

Example:

```php
// Set gravatars size 64x64px
$gravatar->setSize(64);

// Set gravatars size 100x100px
$gravatar->setSize('100');
```

__WARNING__: If an invalid size (less than 0, greater than 2048) or a non-integer value is specified, this method will throw an exception of class `\InvalidArgumentException`.

### Default Image

Gravatar provides several pre-fabricated default images for use when the email address provided does not have a gravatar or when the gravatar specified exceeds your maximum allowed content rating. In addition, you can also set your own default image to be used by providing a valid URL to the image you wish to use.

There are a few conditions which must be met for default image URL:

* __MUST__ be publicly available (e.g. cannot be on an intranet, on a local development machine, behind HTTP Auth or some other firewall etc). Default images are passed through a security scan to avoid malicious content
* __MUST__ be accessible via HTTP or HTTPS on the standard ports, 80 and 443, respectively
* __MUST__ have a recognizable image extension (jpg, jpeg, gif, png)
* __MUST NOT__ include a query string (if it does, it will be ignored)

Possible values:

* `404` — do not load any image if none is associated with the email, instead return an HTTP 404 (File Not Found) response
* `mm` — (mystery-man) a simple, cartoon-style silhouetted outline of a person (does not vary by email)
* `identicon` — a geometric pattern based on an email
* `monsterid` — a generated 'monster' with different colors, faces, etc
* `wavatar` — generated faces with differing features and backgrounds
* `retro` — awesome generated, 8-bit arcade-style pixelated faces
* `blank` — a transparent PNG image
* boolean false — if using the default gravatar image
* Image URL

Example:

```php
// boolean false for the gravatar default
$gravatar->setDefaultImage(false);

// string specifying a recognized gravatar "default"
$gravatar->setDefaultImage('identicon');

// string with image URL
$gravatar->setDefaultImage("http://example.com/your-default-image.png");
```

__WARNING__: If an invalid default image is specified (both an invalid prefab default image and an invalid URL is provided), this method will throw an exception of class `\InvalidArgumentException`.

### Using secure connection

Should we use the secure (HTTPS) URL base? If your site is served over HTTPS, you'll likely want to serve gravatars over HTTPS as well to avoid "mixed content warnings". 

Example:

```php
// Enable secure connections:
$gravatar->enableSecureURL();

// Disable secure connections:
$gravatar->disableSecureURL();
```

To check to see if you are using "secure images" mode, call the method `Gravatar::useSecureURL()`, which will return a boolean value regarding whether or not secure images mode is enabled.

### Gravatar Rating

Gravatar provides four levels for rating avatars by, which are named similar to entertainment media ratings scales used in the United States.

Possible values:

`g` — suitable for display on all websites with any audience type
`pg` — may contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence
`r` — may contain such things as harsh profanity, intense violence, nudity, or hard drug use
`x` — may contain hardcore sexual imagery or extremely disturbing violence

By default, the Gravater rating is `g`.

Example:

```php
$gravatar->setRating('PG');
```

__WARNING__: If an invalid maximum rating is specified, this method will throw an exception of class `\InvalidArgumentException`.

### Force Default

If for some reason you wanted to force the default image to always load, you can enable or disable it.

Example:

```php
// Enable
$gravatar->enableForceDefault();

// Disable
$gravatar->disableForceDefault();
```

To check to see if you are using "Force Default" mode, call the method `Gravatar::useForceDefault()`, which will return a boolean value.


## Contributing

So, you want to contribute to the Gleez Gravatar project? Well of course you do! I guess that's pretty clear. I mean, anyone could tell, right? 

Some things to watch out for:

* Please do PR to `next` branch
* Make sure that the code you write fits with the general style and coding standarts of the [Accepted PHP Standards](http://www.php-fig.org/psr/)
* Your code should be easy to understand, maintainable, and modularized
* Please _test_ your changes before submitting to make sure that not only they work, but have not broken other parts of the Gleez Gravatar
* If you do any API changes please follow [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html)


## Tests

To run the tests at the command line, go to the `tests` directory and run `phpunit`:

```bash
$ cd tests
$ phpunit \
--coverage-text \
--coverage-clover ../build/logs/clover.xml \
--coverage-html   ../build/report
```

For additional information see [PHPUnit The Command-Line Test Runner](http://phpunit.de/manual/current/en/textui.html)


## License

Licensed under the [Gleez CMS License](http://gleezcms.org/license). © Gleez Technologies