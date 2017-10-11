# Changelog
All Notable changes to `jobs-multi` will be documented in this file.

## 1.1.0 - 2017-02-14

### Added
- Support for [Monster RSS feed](https://github.com/jobapis/jobs-monster)
- Support for [Jobs2Careers API](https://github.com/jobapis/jobs-jobs2careers)

## 1.0.1 - 2016-12-23

### Fixed
- Updating jobs-common to fix bug in source name.

## 1.0.0 - 2016-12-01

### Added
- Requirement on `jobs-common` ^2.1.0 for new Collection methods.
- Options for `getAllJobs` and `getJobs` method.
- Making integration tests run every time.

### Fixed
- Moving query instantiation to the end of the process to prevent overwriting queries.
- Improved test coverage.

### Removed
- `Get<Provider>Jobs` Method. Now you must use `getJobsByProvider` or `getAllJobs`.

## 0.9.0 - 2016-11-29

### Added
- Support for [Stack Overflow Jobs](https://github.com/jobapis/jobs-stackoverflow)

### Fixed
- Updated list of providers in readme

## 0.8.0 - 2016-11-15

### Added
- Support for [IEEE JobSite](https://github.com/jobapis/jobs-ieee)

## 0.7.0 - 2016-11-09

### Added
- Support for [Careerjet](https://github.com/jobapis/jobs-careerjet)


## 0.6.1 - 2016-11-01

### Fixed
- Updating Careerbuilder package to fix bug.


## 0.6.0 - 2016-10-29

### Added
- Support for [Jobinventory](https://github.com/jobapis/jobs-jobinventory)


## 0.5.3 - 2016-10-29

### Fixed
- Using Careerbuilder Location as Facets seems to cause issues.


## 0.5.2 - 2016-10-14

### Fixed
- Updating composer to fix Careerbuilder API bug.


## 0.5.1 - 2016-10-11

### Fixed
- Failing build because of argument default order.


## 0.5.0 - 2016-10-10

### Added
- Support for [Ziprecruiter](https://github.com/jobapis/jobs-ziprecruiter)

### Fixed
- Wrapping calls to API Provider `getJobs` methods in try/catch block, returning error collections when appropriate.


## 0.4.1 - 2016-10-06

### Fixed
- Upgrading dependencies with bug fixes.


## 0.4.0 - 2016-10-03

### Added
- Support for [Juju](https://github.com/jobapis/jobs-juju)

### Fixed
- Alphabetizing calls to providers.


## 0.3.2 - 2016-09-25

### Removed
- composer.lock file.


## 0.3.1 - 2016-09-22

### Added
- New providers to documentation.


## 0.3.0 - 2016-09-22

### Added
- Support for [Careercast](https://github.com/jobapis/jobs-careercast)


## 0.2.0 - 2016-09-17

### Added
- Support for [USAJobs](https://github.com/jobapis/jobs-usajobs)


## 0.1.0 - 2016-09-11

### Added
- Initial pre-release.
- Support for 5 initial APIs.
- Unit and integration tests.

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
