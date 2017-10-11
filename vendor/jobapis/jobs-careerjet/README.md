# Careerjet Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-careerjet.svg?style=flat-square)](https://github.com/jobapis/jobs-careerjet/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-careerjet/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-careerjet)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-careerjet.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-careerjet/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-careerjet.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-careerjet)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-careerjet.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-careerjet)

This package provides [Careerjet Jobs Partners API](http://www.careerjet.com/partners/) support for [Jobs Common](https://github.com/jobapis/jobs-common).

## Installation

To install, use composer:

```
composer require jobapis/jobs-careerjet
```

## Usage
Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\CareerjetQuery([
    'affid' => YOUR_AFFILIATE_ID
]);
```

Or via the "set" method. All of the parameters documented in Indeed's documentation can be added.

```php
// Add parameters via the set() method
$query->set('keywords', 'engineering');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('location', 'Chicago, IL')
    ->set('page', '2');
    ->set('pagesize', '100');
```
 
Then inject the query object into the provider.

```php
// Instantiating an IndeedProvider with a query object
$client = new JobApis\Jobs\Client\Provider\CareerjetProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-careerjet/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-careerjet/blob/master/LICENSE) for more information.
