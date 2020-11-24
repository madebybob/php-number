<?php

namespace Number\Tests;

use Locale;
use Number\Exception\DecimalExponentError;
use Number\Exception\DivisionByZeroError;
use Number\Exception\InvalidNumberInputTypeException;
use Number\Money;
use Number\Number;
use PHPUnit\Framework\TestCase;
use stdClass;

class NumberTest extends TestCase
{

    public function testCanInitializeFromString(): void
    {
        $number = new Number('200');

        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('200.00', $number->toString(2));
        $this->assertEquals('200', $number->toString(0));
    }

    public function testCanInitializeFromInteger(): void
    {
        $number = new Number(200);

        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('200.00', $number->toString(2));
        $this->assertEquals('200', $number->toString(0));

        $number = new Number(-200);

        $this->assertEquals('-200.0000', $number->toString());
        $this->assertEquals('-200.00', $number->toString(2));
        $this->assertEquals('-200', $number->toString(0));
    }

    public function testCanInitializeFromFloat(): void
    {
        $number = new Number(200.25);

        $this->assertEquals('200.2500', $number->toString());
        $this->assertEquals('200.25', $number->toString(2));
        $this->assertEquals('200', $number->toString(0));

        $number = new Number(-200.25);

        $this->assertEquals('-200.2500', $number->toString());
        $this->assertEquals('-200.25', $number->toString(2));
        $this->assertEquals('-200', $number->toString(0));
    }

    public function testCannotInitializeFromNull(): void
    {
        $this->expectException(InvalidNumberInputTypeException::class);
        new Number(null);
    }

    public function testCannotInitializeFromObject(): void
    {
        $this->expectException(InvalidNumberInputTypeException::class);
        new Number(new stdClass());
    }

    public function testCannotInitializeFromBool(): void
    {
        $this->expectException(InvalidNumberInputTypeException::class);
        new Number(true);
    }

    public function testCanAddStringAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->add('400');

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('600.0000', $result->toString());
        $this->assertTrue($result->isPositive());
    }

    public function testCanAddFloatAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->add(400.5);

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('600.5000', $result->toString());
        $this->assertTrue($result->isPositive());
    }

    public function testCanAddIntegerAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->add(400);

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('600.0000', $result->toString());
        $this->assertTrue($result->isPositive());
    }

    public function testCanAddNumberAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->add(new Number('400'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('600.0000', $result->toString());
        $this->assertTrue($result->isPositive());
    }

    public function testCannotAddArray(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->add([]);
    }

    public function testCannotAddObject(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->add(new \stdClass());
    }

    public function testCannotAddBoolean(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->add(true);
    }

    public function testCannotAddNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->add(null);
    }

    public function testCanSubtractNumberAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->subtract(new Number('50'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('150.0000', $result->toString());

        // alias sub
        $number = new Number('200');
        $result = $number->sub(new Number('50'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('150.0000', $result->toString());

        // alias minus
        $number = new Number('200');
        $result = $number->minus(new Number('50'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('150.0000', $result->toString());
    }

    public function testCanDivideNumberAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->divide(new Number('5'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('40.0000', $result->toString());

        // alias sub
        $number = new Number('200');
        $result = $number->div(new Number('4'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('50.0000', $result->toString());
    }

    public function testCanNotDivideByZero(): void
    {
        $number = new Number('200');

        $this->expectException(DivisionByZeroError::class);
        $number->divide('0.0000');
    }

    public function testCanNotDivideByNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->divide(null);
    }

    public function testCanNotDivideNullWithFallback(): void
    {
        $number = new Number('200');

        $zero = $number->divide('0.0000', null, '0.0000');
        $this->assertEquals('0.0000', $zero->toString());

        $zero = $number->divide('0.0000', null, '20.0000');
        $this->assertEquals('20.0000', $zero->toString());
    }

    public function testCanMultiplyNumberAsImmutable(): void
    {
        $number = new Number('200');
        $result = $number->multiply(new Number('50'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('10000.0000', $result->toString());

        // alias mul
        $number = new Number('200');
        $result = $number->mul(new Number('4'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('200.0000', $number->toString());
        $this->assertEquals('800.0000', $result->toString());
    }

    public function testCannotMultiplyArray(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->multiply([]);
    }

    public function testCannotMultiplyObject(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->multiply(new \stdClass());
    }

    public function testCannotMultiplyBoolean(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->multiply(true);
    }

    public function testCannotMultiplyNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->multiply(null);
    }

    public function testCanGetModulusNumberAsImmutable(): void
    {
        $number = new Number('50');
        $result = $number->modulus(new Number('20'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('50.0000', $number->toString());
        $this->assertEquals('10.0000', $result->toString());

        // big number
        $number = new Number('50');
        $result = $number->modulus(new Number('22.5683'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('50.0000', $number->toString());
        $this->assertEquals('4.8634', $result->toString());

        // alias mod
        $number = new Number('44');
        $result = $number->mod(new Number('40'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('44.0000', $number->toString());
        $this->assertEquals('4.0000', $result->toString());
    }

    public function testCannotGetModulusArray(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->modulus([]);
    }

    public function testCannotGetModulusObject(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->modulus(new \stdClass());
    }

    public function testCannotGetModulusBoolean(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->modulus(true);
    }

    public function testCannotGetModulusNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->modulus(null);
    }

    public function testCanRaiseNumberToThePowerOfValueAsImmutable(): void
    {
        $number = new Number('9');
        $result = $number->pow(new Number('3'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('9.0000', $number->toString());
        $this->assertEquals('729.0000', $result->toString());
    }

    public function testCannotRaiseNumberToThePowerOfDecimal(): void
    {
        $number = new Number('200');

        $this->expectException(DecimalExponentError::class);
        $number->pow('28.75');
    }

    public function testCannotRaiseNumberToThePowerOfArray(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->pow([]);
    }

    public function testCannotRaiseNumberToThePowerOfObject(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->pow(new \stdClass());
    }

    public function testCannotRaiseNumberToThePowerOfBoolean(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->pow(true);
    }

    public function testCannotRaiseNumberToThePowerOfNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->pow(null);
    }

    public function testCanGetModulusNumberByThePowerOfValueAsImmutable(): void
    {
        $number = new Number('9');
        $result = $number->powmod(new Number('4'), new Number('5'));

        $this->assertInstanceOf(Number::class, $result);
        $this->assertEquals('9.0000', $number->toString());
        $this->assertEquals('1.0000', $result->toString());
    }

    public function testCannotGetModulusNumberToThePowerOfDecimal(): void
    {
        $number = new Number('200');

        $this->expectException(DecimalExponentError::class);
        $number->powmod('28.75', '2');
    }

    public function testCannotGetModulusNumberToThePowerOfArray(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->powmod([], '2');
    }

    public function testCannotGetModulusNumberToThePowerOfObject(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->powmod(new \stdClass(), '2');
    }

    public function testCannotGetModulusNumberToThePowerOfBoolean(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->powmod(true, '2');
    }

    public function testCannotGetModulusNumberToThePowerOfNull(): void
    {
        $number = new Number('200');

        $this->expectException(InvalidNumberInputTypeException::class);
        $number->powmod(null, '2');
    }

    public function testIsPositive(): void
    {
        $this->assertTrue((new Number('200'))->isPositive());
        $this->assertTrue((new Number('1'))->isPositive());

        $this->assertFalse((new Number('200'))->isNegative());
        $this->assertFalse((new Number('1'))->isNegative());
        $this->assertFalse((new Number('0'))->isNegative());
    }

    public function testIsNegative(): void
    {
        $this->assertTrue((new Number('-200'))->isNegative());
        $this->assertTrue((new Number('-1'))->isNegative());

        $this->assertFalse((new Number('-200'))->isPositive());
        $this->assertFalse((new Number('-1'))->isPositive());
    }

    public function testIsZero(): void
    {
        $this->assertTrue((new Number('-0'))->isZero());
        $this->assertTrue((new Number('0'))->isZero());

        $this->assertFalse((new Number('-200'))->isZero());
        $this->assertFalse((new Number('200'))->isZero());
        $this->assertFalse((new Number('-1'))->isZero());
        $this->assertFalse((new Number('1'))->isZero());
        $this->assertFalse((new Number('0.000000085'))->isZero());
    }

    public function testIsGreaterThan(): void
    {
        $five = new Number(5);
        $this->assertTrue($five->isGreaterThan('3'));
        $this->assertFalse($five->isGreaterThan('7'));

        $this->assertTrue($five->isGreaterThan('-500'));
        $this->assertTrue($five->isGreaterThan('4.9999'));
        $this->assertFalse($five->isGreaterThan('5.0001'));
    }

    public function testIsLessThan(): void
    {
        $five = new Number(5);
        $this->assertFalse($five->isLessThan('3'));
        $this->assertTrue($five->isLessThan('7'));

        $this->assertFalse($five->isLessThan('-500'));
        $this->assertFalse($five->isLessThan('4.9999'));
        $this->assertTrue($five->isLessThan('5.0001'));
    }

    public function testIsEqual(): void
    {
        $five = new Number(5);
        $this->assertTrue($five->isEqual('5'));
        $this->assertTrue($five->isEqual('5.0000'));

        $this->assertFalse($five->isEqual('-5'));
        $this->assertFalse($five->isEqual('4.9999'));
        $this->assertFalse($five->isEqual('5.0001'));
    }

    public function testCanTraceByParent(): void
    {
        $five = new Number(5);
        $seven = $five->add(2);
        $fifteen = $seven->add(8);

        $this->assertEquals($five->toString(), '5.0000');
        $this->assertEquals($seven->toString(), '7.0000');
        $this->assertEquals($fifteen->toString(), '15.0000');

        $this->assertEquals($seven->parent()->toString(), '5.0000');
        $this->assertEquals($fifteen->parent()->toString(), '7.0000');
        $this->assertEquals($fifteen->parent()->parent()->toString(), '5.0000');
        $this->assertNull($five->parent());
    }

    public function testCanInitializeMoney(): void
    {
        // Instance initialized by constructor.
        $five = new Money(5, 'EUR');
        $this->assertEquals($five->toString(), '5.0000');
        $this->assertEquals($five->isoCode(), 'EUR');

        // Instance initialized by init().
        $seven = $five->add(2);
        $this->assertEquals($seven->toString(), '7.0000');
        $this->assertEquals($seven->isoCode(), 'EUR');

        $this->assertEquals($seven->parent(), $five);
    }

    public function testFormatNumber(): void
    {
        Locale::setDefault('nl_NL');

        // Round up, default 4 fraction digits
        $number = new Number('9342.15579453');
        $this->assertEquals('9.342,16', $number->format());

        $number = new Number('9342.15578453');
        $this->assertEquals('9.342,15578453', $number->format(0, 8));

        $number = new Number('5943.000000');
        $this->assertEquals('5.943', $number->format(0, 0));
    }

    public function testFormatMoney(): void
    {
        Locale::setDefault('nl_NL');

        $money = new Money('9342.1539', 'EUR');
        $this->assertEquals('€ 9.342,15', $money->format());

        $money = new Money('5.8393', 'EUR');
        $this->assertEquals('€ 5,84', $money->format());
    }
}
