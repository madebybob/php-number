# Changelog

All notable changes to `php-number` will be documented in this file

## 2.2.3 - 2022-10-24

### What's Changed

- Make `Number::create` accept null values by @bobmulder in https://github.com/madebybob/php-number/pull/13

**Full Changelog**: https://github.com/madebybob/php-number/compare/2.2.2...2.2.3

## 2.2.2 - 2022-10-24

### What's Changed

- Fix dependencies for Vimeo and PHP CS to fix workflow

﻿**Full Changelog**: https://github.com/madebybob/php-number/compare/2.2.1...2.2.2

## 2.2.1 - 2022-10-24

### What's Changed

- Add jsonSerialize by @melvin-drost in https://github.com/madebybob/php-number/pull/16

### New Contributors

- @melvin-drost made their first contribution in https://github.com/madebybob/php-number/pull/16

**Full Changelog**: https://github.com/madebybob/php-number/compare/2.2.0...2.2.1

## 2.2.0 - 2022-10-22

### What's Changed

- Add `isGreaterThanOrEqual`, `isLessThanOrEqual`, `eq`, `gte` and `lte` by @bobmulder in https://github.com/madebybob/php-number/pull/15

**Full Changelog**: https://github.com/madebybob/php-number/compare/2.1.1...2.2.0

## 2.1.1 - 2022-10-22

- Fix update-changelog workflow

﻿**Full Changelog**: https://github.com/madebybob/php-number/compare/2.1.0...2.1.1

## 2.0.0 - 2021-08-16

- Change root namespace from `Number` to `MadeByBob/Number` ([#12](https://github.com/madebybob/php-number/pull/12))

## 1.4.0 - 2021-03-04

- Added `absolute` and `opposite` methods in `AbstractNumber` ([#10](https://github.com/madebybob/php-number/pull/10))
- Fixed bug in `isPositive` method ([#10](https://github.com/madebybob/php-number/pull/10))

## 1.3.0 - 2021-01-21

- Added `round`, `ceil` & `floor` method in `AbstractNumber`
- Improved unit tests when Intl extension is not loaded

## 1.2.0 - 2021-01-20

- Added PHP 8 support + type fix ([#8](https://github.com/madebybob/php-number/pull/8) by [@affektde](https://github.com/affektde))

## 1.1.0 - 2021-01-05

- Removed `AbstractNumber::create()` in favor of extensibility. Added to the default `Number` class.
- (it was not possible to extend the method by adding arguments in `AbstractNumber` implementations).
- Add this method to your own `AbstractNumber` implementations if you like to be able to use this static factory method.
- Changed access identifier of `AbstractNumber::getNumberFromInput()` from `private` to `protected` in favor of extensibility.

## 1.0.0 - 2020-12-30

- Initial release
