# Github Jobs Client

[![Latest Version](https://img.shields.io/github/release/jobapis/jobs-github.svg?style=flat-square)](https://github.com/jobapis/jobs-github/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/jobapis/jobs-github/master.svg?style=flat-square&1)](https://travis-ci.org/jobapis/jobs-github)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jobapis/jobs-github.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-github/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jobapis/jobs-github.svg?style=flat-square)](https://scrutinizer-ci.com/g/jobapis/jobs-github)
[![Total Downloads](https://img.shields.io/packagist/dt/jobapis/jobs-github.svg?style=flat-square)](https://packagist.org/packages/jobapis/jobs-github)

This package provides [Github Jobs API](https://jobs.github.com/api)
support for the [Jobs Common](https://github.com/jobapis/jobs-common) project.

## Installation

To install, use composer:

```
composer require jobapis/jobs-github
```

## Usage


Create a Query object and add all the parameters you'd like via the constructor.
 
```php
// Add parameters to the query via the constructor
$query = new JobApis\Jobs\Client\Queries\GithubQuery([
    'search' => 'engineering'
]);
```

Or via the "set" method. All of the parameters documented in the API's documentation can be added.

```php
// Add parameters via the set() method
$query->set('location', 'Chicago, IL');
```

You can even chain them if you'd like.

```php
// Add parameters via the set() method
$query->set('page', '2')
    ->set('full_time', 'true');
```
 
Then inject the query object into the provider.

```php
// Instantiating provider with a query object
$client = new JobApis\Jobs\Client\Provider\GithubProvider($query);
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

Please see [CONTRIBUTING](https://github.com/jobapis/jobs-github/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Steven Maguire](https://github.com/stevenmaguire)
- [Karl Hughes](https://github.com/karllhughes)
- [All Contributors](https://github.com/jobapis/jobs-github/contributors)


## License

The Apache 2.0. Please see [License File](https://github.com/jobapis/jobs-github/blob/master/LICENSE) for more information.
