# PHP Number - Deal with numbers the right way

![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/madebybob/php-number/Tests/master?label=Tests&logo=Tests)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/madebybob/php-number.svg)](https://packagist.org/packages/madebybob/php-number)
[![Total Downloads](https://img.shields.io/packagist/dt/madebybob/php-number.svg)](https://packagist.org/packages/madebybob/php-number)
![GitHub](https://img.shields.io/github/license/madebybob/php-number)

![PHP Number](https://raw.githubusercontent.com/madebybob/php-number/master/.github/art-staartjes.png)

This library aims to deal with numbers like prices, weights and quantities the right way in PHP. 

#### The problem
Have you ever worked with prices, weights, or any other numbers in PHP? What type are they? An integer? A string? Or did 
you get a float to manage decimals? And how can you do calculations with them? We have all struggled with float's [counter-intuitive behavior](https://medium.com/@rtheunissen/accurate-numbers-in-php-b6954f6cd577).

Ahh, after hours of investigation you've found [BC Math](https://www.php.net/manual/en/book.bc.php). Now you can do math 
with your numbers. However, it is still hard to manage those numbers in your codebase. BC Math only accepts and returns 
strings. Type hinting strings when working with numbers as a modern-php-techie is not done.

#### The solution
This library will help you to manage number in your codebase. Using the `Number` class you can typehint them 
(`getTotal(Number $quantity)`) and make calculations on the number itself (`$number->sum('200')'`). Since those methods
are immutable you can chain your methods on them. 

#### Scope
This library aims to make your code cleaner. You can typehint the `Number` class, and you can make cleaner calculations 
(still using BC Math in the background).

We have chosen not to support specific implementations of numbers like money and weights. This is too specific and out of scope.
Mainly, custom implementations of numbers are business specific. In our opinion you should create them yourself, according
to the desired needs of your business.

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Add](#add)
    - [Subtract](#subtract)
    - [Divide](#divide)
    - [Multiply](#multiply)
    - [Modulus](#modulus)
    - [State & Comparison](#state--comparison)
    - [Absolute & opposite values](#absolute--opposite-values)
    - [Limiting values](#limiting-values)
    - [Rounding](#rounding)
    - [Immutable & Chaining](#immutable--chaining)
    - [Extensibility](#extensibility)
- [Testing](#testing--php-cs-fixer)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation
You can install the package via composer:

```bash
composer require madebybob/php-number
```

## Usage
In short, the `Number\Number` class is where it's all about. This is how you can create a `Number` instance:

``` php
use Number\Number;

// with string
$number = new Number('200');

// with integer
$number = new Number(200);

// with float
$number = new Number(200.8);

// via static method
$quantity = Number::create(4);

// calculations
$total = $number->add($quantity);
```

Creating new numbers (or using them in the methods below) supports types like `Number`, string, integer and float.

### Add
To add a new number to your current number instance:

``` php
$total = $number
    ->add('200')
    ->plus('200');
```

### Subtract
To subtract a number from your current number instance:

``` php
$total = $number
    ->subtract('200')
    ->sub('200') // sub is an alias for subtract
    ->minus('200'); // minus is an alias for subtract
```

### Divide
To divide your current number instance into the given number:

``` php
$total = $number
    ->divide('200')
    ->div('200'); // div is an alias for divide
```

Division by zero is not possible, of course. To not break your chain, a fallback value can be used like this:

``` php
$total = $number->divide($variable, null, '1.000');
```

### Multiply
To multiply your current number instance with the given number:

``` php
$total = $number
    ->multiply('200')
    ->mul('200'); // mul is an alias for multiply
```

### Modulus
To get the modulus of the current number instance:

``` php
$newNumber = $number
    ->modulus('200')
    ->mod('200'); // mod is an alias for modulus
```

### Square root
To get the square root of the current number instance:

``` php
$sqrt = $number
    ->sqrt()
    ->squareRoot(); // squareRoot is an alias for sqrt
```

### State & Comparison
To compare two numbers with each other, these helpers are available, which will return a `bool`:

``` php
$number = new Number('200');

// check if the number is positive
$number->isPositive();

// check if the number is negative
$number->isNegative();

// check if the number is greater than x
$number->isGreaterThan('100');

// check if the number is less than x
$number->isLessThan('300');

// check if the number is equal to x
$number->isEqual('200');

// check if the number is zero ("0")
$number->isZero();
```

### Absolute & opposite values
To get the absolute (positive) value of the current instance:

``` php
$number = new Number('-200');

// $absolute will be 200
$absolute = $number->absolute();

// abs is an alias for absolute
$abs = $number->abs();
```

To get the opposite value of the current instance:

``` php
$number = new Number('200');

// $absolute will be -200
$absolute = $number->opposite();

// opp is an alias for absolute
$abs = $number->opp();
```

### Limiting values
To make sure the current number is not higher or lower than expected:

```php
$number = new Number('200');

// $result will be 250
$result = $number->min('250');

// $result will be 100
$result = $number->max('100');
```

To use a min and max 'clamp' at the same time:

```php
$number = new Number('200');

// $result will be 150
$result = $number->clamp('100', '150');
```

### Rounding
To round the current number instance, the following methods are available:

``` php
$number = new Number('200.5000');

// rounds the number to '201.0000'
$number->round();

// ceils the number to '201.0000'
$number->ceil();

// floors the number to '200.0000'
$number->floor();
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

## Extensibility
We encourage you to create custom implementations of the `AbstractNumber` class for your specific use cases.  This enables 
you to type hint much better which type of number you expect and how they should be formatted.

[PHP Number - Examples](https://github.com/madebybob/php-number-examples) is a repository dedicated to showing how a custom 
number type like weights should be implemented. Please check out this repository for further documentation about the 
extensibility of this package.

## Testing & Php CS Fixer 
``` bash
composer test
```

```` bash
./vendor/bin/php-cs-fixer fix
````

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Supporters
[![Stargazers repo roster for @madebybob/php-number](https://reporoster.com/stars/madebybob/php-number)](https://github.com/madebybob/php-number/stargazers)

## Credits
- [Bob Mulder](https://github.com/bobmulder)
- [Jon Mulder](https://github.com/jonmldr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
