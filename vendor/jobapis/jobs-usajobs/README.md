# USAJOBS Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-usajobs.svg?style=flat-square)](https://github.com/jobapis/jobs-usajobs/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-usajobs/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-usajobs)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-usajobs.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-usajobs/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-usajobs.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-usajobs)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-usajobs.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-usajobs)

This package provides [USAJOBS Job Search API ](https://developer.usajobs.gov/Search-API/Overview)
support for [Jobs Common](https://github.com/jobapis/jobs-common).

## Installation

To install, use composer:

```
composer require jobapis/jobs-usajobs
```

## Usage

Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\UsajobsQuery([
    'AuthorizationKey' => YOUR_API_KEY
]);
```

Or via the "set" method. All of the parameters documented can be added.

```php
// Add parameters via the set() method
$query->set('Keyword', 'engineering');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('LocationName', 'Chicago, IL')
    ->set('JobCategoryCode', '1234');
```
 
Then inject the query object into the provider.

```php
// Instantiating provider with a query object
$client = new JobApis\Jobs\Client\Provider\UsajobsProvider($query);
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

If you want to run a complete integration test, you need to provide your API Key:
``` bash
$ AUTHORIZATION_KEY=<YOUR_API_KEY> ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](/CONTRIBUTING.md) for details.

## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-usajobs/contributors)

## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-usajobs/blob/master/LICENSE) for more information.
