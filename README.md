# PHP Number - Deal with numbers the right way

![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/madebybob/php-number/Tests/master?label=Tests&logo=Tests)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/madebybob/php-number.svg)](https://packagist.org/packages/madebybob/php-number)
[![Total Downloads](https://img.shields.io/packagist/dt/madebybob/php-number.svg)](https://packagist.org/packages/madebybob/php-number)
![GitHub](https://img.shields.io/github/license/madebybob/php-number)

![PHP Number](https://raw.githubusercontent.com/madebybob/php-number/master/.github/art-staartjes.png)

This library aims to deal with numbers like prices, weights, quantities, et cetera.

#### The problem
Have you ever worked with prices, weights, or any other numbers in PHP? What type are they? An integer? A string? Or did 
you get a float to manage decimals? And how can you do calculations with them?

Ahh, after hours of investigation you've found [bcmath](https://www.php.net/manual/en/book.bc.php), so you can do math 
with your numbers. But it is still hard to manage your numbers in your codebase, because you cannot typehint anything.

#### The solution
This library will help you to manage number in your codebase. Using the `Number` class you can typhint them 
(`getTotal(Number $quantity)`) and make calculations on the number itself (`$number->sum('200')'`). Since those methods
are immutable you can chain your methods on them. 

#### Scope
This library aims to make your code cleaner. You can typehint the `Number` class, and you can make cleaner calculations.
We have chosen not to support specific implementations of numbers like Money. This is too specific and out of scope.
Instead, you can create custom `Number` implementations and create your own `format` method. This way, you can be even 
more specific with your types, which is all of your responsibility.

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Add](#add)
    - [Subtract](#subtract)
    - [Divide](#divide)
    - [Multiply](#multiply)
    - [Modulus](#modulus)
    - [State & Comparison](#state--comparison)
    - [Immutable & Chaining](#immutable--chaining)
    - [Custom implementations](#custom-implementations)
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

$number = new Number('200'); // from string
$number = new Number(200); // from integer
$number = new Number(200.8); // from float

$quantity = new Number(4);

$total = $number->add($quantity); // from Number instance
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

## Custom implementations
We encourage you to create custom implementations of the `AbstractNumber` class for specific use cases. This enables you 
to type hint much better which type of number you expect. A nice example is a custom `Money` class:

``` php
class Money extends AbstractNumber
{
    public function format(string $isoCode): string
    {
        return Formatter::formatMoney($this->get(), $isoCode);
    }
}
```

Now you can easily specify your types much better e.g.:

```php
public function calculateVatAmount(Money $amount, Number $percentage): Money
{
    $vatAmount = $amount->divide(100, 0)->multiply($percentage);

  	if ($vatAmount->isNegative()) {
    	return Money::create(0);
    }

    return $vatAmount;
}
```

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

## Credits
- [Bob Mulder](https://github.com/bobmulder)
- [Jon Mulder](https://github.com/jonmldr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
