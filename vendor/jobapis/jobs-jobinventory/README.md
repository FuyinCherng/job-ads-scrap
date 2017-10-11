# Jobinventory Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-jobinventory.svg?style=flat-square)](https://github.com/jobapis/jobs-jobinventory/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-jobinventory/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-jobinventory)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-jobinventory.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobinventory/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-jobinventory.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-jobinventory)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-jobinventory.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-jobinventory)

This package provides [Jobinventory](http://www.jobinventory.com/) RSS feed support for [Jobs Common](https://github.com/jobapis/jobs-common).

## Installation

To install, use composer:

```
composer require jobapis/jobs-jobinventory
```

## Usage
Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\JobinventoryQuery();
```

Or via the "set" method. All of the parameters documented in Indeed's documentation can be added.

```php
// Add parameters via the set() method
$query->set('q', 'engineering');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('l', 'Chicago, IL')
    ->set('limit', '20');
```
 
Then inject the query object into the provider.

```php
// Instantiating a provider with a query object
$client = new JobApis\Jobs\Client\Provider\JobinventoryProvider($query);
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

Please see [CONTRIBUTING](https://github.com/jobapis/jobs-jobinventory/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-jobinventory/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-jobinventory/blob/master/LICENSE) for more information.
