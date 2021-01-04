# Changelog

All notable changes to `php-number` will be documented in this file

## 2.0.0

- Removed `AbstractNumber::create()` in favor of extensibility. Added to the default `Number` class.
(it was not possible to extend the method by adding arguments in `AbstractNumber` implementations).
Add this method to your own `AbstractNumber` implementations if you like to be able to use this static factory method.

## 1.0.0 - 202X-XX-XX

- initial release
