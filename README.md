# PHP Number - Deal with numbers the right way

This library aims to deal with numbers like prices, weights, quantities, et cetera.

#### The problem
Have you ever worked with prices, weights, or any other numbers in PHP? What type are they? Integer? A String? Or did you get a float to manage decimals?

Ahh, after hours of investigation you've found [bcmath](https://www.php.net/manual/en/book.bc.php), but it is still hard to manage your numbers in your codebase. While `bcmath` is a great library to make calculations with, the API is not easy to use.

#### The solution
This library will help you to manage numbers because you can typhint them (`getTotal(Number $quantity)`) and make calculations on the number itself (`$number->sum('200')'`).


## Installation

You can install the package via composer:

```bash
composer require madebybob/php-number
```

## Usage

In short, the `Madebybob\Number\Number` class is where it's all about.

A short overview of what you can do with the class:

### Instance

``` php
use Madebybob\Number\Number;

// from string
$number = new Number('200');

// from integer
$number = new Number(200);
```

### Add

``` php
use Madebybob\Number\Number;

$newNumber = $number->add('200');
```

### Subtract

``` php
use Madebybob\Number\Number;

$newNumber = $number->subtract('200');
```

### State

``` php
use Madebybob\Number\Number;

$number = new Number('200');

// check if the number is positive
$number->isPositive();

// check if the number is negative
$number->isNegative();

// check if the number is zero ("0")
$number->isZero();
```

### Immutable & Chaining

Since the `Number` class is immutable and most methods will return a new `Number` instance you will be able to chain your calculations like so:

``` php
$number = new Number('200');

$result = $number
    ->add(new Number('200'))
    ->subtract($otherValue)
    ->add('400');

if ($result->isPositive()) {
    return $result->toString();
}
```
 
## Testing

``` bash
composer test
```

## Wishlist

There is a lot to do since this project is experimental. Feel free to make suggestions through a Github Issue or push your own contribution via a Pull Request. Don't be afraid you'll break anything; we got you covered by tests :)

- [ ] Add `Formatter` class to format every number (like price, weight, et cetera) into a plain Number object
- [ ] Improve `Formatter` class to format `Number` instance into human readable numbers like price and weight
- [ ] Implement `bccomp`
- [ ] Implement `bcdiv`
- [ ] Implement `bcmod`
- [ ] Implement `bcmul`
- [ ] Implement `bcpow`
- [ ] Implement `bcpowmod`
- [ ] Implement `bcscale`
- [ ] Implement `bcsqrt`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Bob Mulder](https://github.com/bobmulder)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
