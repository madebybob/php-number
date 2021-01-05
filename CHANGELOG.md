# Changelog

All notable changes to `php-number` will be documented in this file

## 1.1.0 - 2021-01-05
- Removed `AbstractNumber::create()` in favor of extensibility. Added to the default `Number` class.
(it was not possible to extend the method by adding arguments in `AbstractNumber` implementations).
Add this method to your own `AbstractNumber` implementations if you like to be able to use this static factory method.
- Changed access identifier of `AbstractNumber::getNumberFromInput()` from `private` to `protected` in favor of extensibility. 

## 1.0.0 - 2020-12-30
- Initial release
