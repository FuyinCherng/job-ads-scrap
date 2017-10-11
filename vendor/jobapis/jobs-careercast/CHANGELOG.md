# Changelog
All Notable changes to `jobs-careercast` will be documented in this file

## 1.0.1 - 2016-10-06

### Fixed
- DatePosted and ValidThrough on Job object now being saved as object instead of string. 

## 1.0.0 - 2016-09-22

### Added
- Support for V2 of jobs common
- Real API call integration test

### Changed
- Default to JSON output format which limits the input parameters, but allows more detailed job objects.

## 0.2.0 - 2015-10-19

### Added
- Support for more setters in RSS feed query strings
- Readme documentation for supported methods

### Deprecated
- Nothing

### Fixed
- Sorting methods alphabetically
- Travis-ci support for PHP 7.0 and HHVM

### Removed
- setCity and setState methods not supported by official API

### Security
- Nothing

## 0.1.3 - 2015-08-12

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Updated to v1.0.3 of jobs-common

### Removed
- getParameters and parseAsXml methods

### Security
- Nothing

## 0.1.2 - 2015-07-25

### Added
- Support for name attribute
- Date object for postedDate
- Parsing company name from description field

### Deprecated
- Nothing

### Fixed
- Setting city and state methods based on query
- Description missing in CareerCast because of CDATA field in rss output

### Removed
- Nothing

### Security
- Nothing

## 0.1.1 - 2015-07-04

### Added
- Support for version 1.0 release of jobs-common project

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## 0.1.0 - 2015-06-07

### Added
- Initial release with tests

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
