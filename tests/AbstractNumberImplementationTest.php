<?php

namespace Number\Tests;

use Locale;
use Number\Tests\TestClasses\Money;
use PHPUnit\Framework\TestCase;

class AbstractNumberImplementationTest extends TestCase
{
    public function testCanInitializeAbstractNumberImplementation(): void
    {
        // Instance initialized by constructor.
        $five = new Money(5);
        $this->assertEquals($five->toString(), '5.0000');

        // Instance initialized by init().
        $seven = $five->add(2);
        $this->assertEquals($seven->toString(), '7.0000');

        // Test parent
        $this->assertEquals($seven->parent(), $five);

        // Instance initialized by static method.
        $ten = Money::create(10);
        $this->assertEquals($ten->toString(0), '10');
    }

    public function testFormatAbstractNumberImplementation(): void
    {
        Locale::setDefault('nl_NL');

        $money = new Money('9342.1539');
        $this->assertEquals('€ 9.342,15', $money->format('EUR'));

        $money = new Money('5.8393');
        $this->assertEquals('€ 5,84', $money->format('EUR'));
    }
}
