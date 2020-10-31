# PHP Number - Deal with numbers the right way

![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/madebybob/php-number/Tests/master?label=Tests&logo=Tests)
![GitHub](https://img.shields.io/github/license/madebybob/php-number)

This library aims to deal with numbers like prices, weights, quantities, et cetera.

#### The problem
Have you ever worked with prices, weights, or any other numbers in PHP? What type are they? Integer? A String? Or did you get a float to manage decimals?

Ahh, after hours of investigation you've found [bcmath](https://www.php.net/manual/en/book.bc.php), but it is still hard to manage your numbers in your codebase. While `bcmath` is a great library to make calculations with, the API is not easy to use.

#### The solution
This library will help you to manage numbers because you can typhint them (`getTotal(Number $quantity)`) and make calculations on the number itself (`$number->sum('200')'`).

## Table of Contents



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

$number = new Number('200'); // from string
$number = new Number(200); // from integer
$number = new Number(200.8); // from float
```

### Add

``` php
use Madebybob\Number\Number;

$newNumber = $number
    ->add('200')
    ->plus('200');
```

### Subtract

``` php
use Madebybob\Number\Number;

$newNumber = $number
    ->subtract('200')
    ->sub('200')
    ->minus('200');
```

### Divide

``` php
use Madebybob\Number\Number;

$newNumber = $number
    ->divide('200')
    ->div('200');
```

### Multiply

``` php
use Madebybob\Number\Number;

$newNumber = $number
    ->multiply('200')
    ->mul('200');
```

### Modulus

``` php
use Madebybob\Number\Number;

$newNumber = $number
    ->modulus('200')
    ->mod('200');
```

### Comparison

``` php
use Madebybob\Number\Number;

$number = new Number('200');

// check if the number is positive
$number->isGreaterThan('100');

// check if the number is negative
$number->isLessThan('300');

// check if the number is zero ("0")
$number->isEqual('100');
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

Since the `Number` class is immutable, most methods will return a new `Number` instance.

``` php
$two = new Number(2);
$four = $two->plus(2);

echo $two->toString(); // $two is still 2
```

Because the mathematical methods are fluent, you will be able to chain your calculations like so:

``` php
$number = new Number('200');

$result = $number
    ->add(200)
    ->subtract(109.5)
    ->mul($two)
    ->toString();
```

## Testing

``` bash
composer test
```

## Php CS Fixer
```` bash
./vendor/bin/php-cs-fixer fix
````

## Wishlist

There is a lot to do since this project is experimental. Feel free to make suggestions through a Github Issue or push your own contribution via a Pull Request. Don't be afraid you'll break anything; we got you covered by tests :)

- [x] Add `Formatter` class to format implementations of the AbstractFormatter class
- [x] Implement `bccomp`
- [x] Implement `bcdiv`
- [x] Implement `bcmod`
- [x] Implement `bcmul`
- [x] Implement `bcpow`
- [ ] Implement `bcpowmod`
- [ ] Implement `bcsqrt`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Bob Mulder](https://github.com/bobmulder)
- [Jon Mulder](https://github.com/jonmldr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
