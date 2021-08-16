# Changelog

All notable changes to `php-number` will be documented in this file

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
(it was not possible to extend the method by adding arguments in `AbstractNumber` implementations).
Add this method to your own `AbstractNumber` implementations if you like to be able to use this static factory method.
- Changed access identifier of `AbstractNumber::getNumberFromInput()` from `private` to `protected` in favor of extensibility. 

## 1.0.0 - 2020-12-30
- Initial release
