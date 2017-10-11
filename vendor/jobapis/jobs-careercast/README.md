# CareerCast Job Board API Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-careercast.svg?style=flat-square)](https://github.com/jobapis/jobs-careercast/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-careercast/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-careercast)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-careercast.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-careercast/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-careercast.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-careercast)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-careercast.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-careercast)

This package provides [CareerCast Jobs](http://www.careercast.com/jobs/results/keyword?format=json)
support for the [Jobs Common project](https://github.com/JobBrander/jobs-common).

## Installation

To install, use composer:

```
composer require jobapis/jobs-careercast
```

## Usage

Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\CareercastQuery([
    'keyword' => 'engineering'
]);
```

Or via the "set" method. All of the parameters documented can be added.

```php
// Add parameters via the set() method
$query->set('location', 'Chicago, IL');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('company', 'General Electric')
    ->set('page', '2');
```
 
Then inject the query object into the provider.

```php
// Instantiating provider with a query object
$client = new JobApis\Jobs\Client\Provider\CareercastProvider($query);
```

And call the "getJobs" method to retrieve results.

```php
// Get a Collection of Jobs
$jobs = $client->getJobs();
```

This will return a [Collection](https://github.com/jobapis/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/jobapis/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobapis/jobs-careercast/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [Steven Maguire](https://github.com/stevenmaguire)
- [All Contributors](https://github.com/jobapis/jobs-careercast/contributors)

## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-careercast/blob/master/LICENSE) for more information.
