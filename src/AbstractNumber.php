<?php

declare(strict_types=1);

namespace Number;

use Number\Exception\DecimalExponentError;
use Number\Exception\DivisionByZeroError;
use Number\Exception\InvalidNumberInputTypeException;
use Number\Exception\InvalidRoundingModeException;

abstract class AbstractNumber
{
    protected const INTERNAL_SCALE = 12;
    protected const DEFAULT_SCALE = 4;

    public const ROUND_HALF_UP = 1;
    public const ROUND_HALF_DOWN = 2;
    public const ROUND_HALF_EVEN = 3;
    public const ROUND_HALF_ODD = 4;
    private const ROUNDING_MODES = [
        self::ROUND_HALF_UP => self::ROUND_HALF_UP,
        self::ROUND_HALF_DOWN => self::ROUND_HALF_DOWN,
        self::ROUND_HALF_EVEN => self::ROUND_HALF_EVEN,
        self::ROUND_HALF_ODD => self::ROUND_HALF_ODD,
    ];

    protected string $value;
    protected ?self $parent;

    /**
     * @param string|float|int $value
     */
    public function __construct($value, ?self $parent = null)
    {
        if (! is_string($value) && ! is_float($value) && ! is_int($value)) {
            throw new InvalidNumberInputTypeException($value);
        }

        $this->value = (string) $value;
        $this->parent = $parent;
    }

    public function init(string $value): self
    {
        return new static($value, $this);
    }

    /**
     * Adds the given value to the current number.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function add($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $sum = bcadd($this->value, $number->get(), $scale);

        return $this->init($sum);
    }

    /**
     * Alias for add method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function plus($value, int $scale = null): self
    {
        return $this->add($value, $scale);
    }

    /**
     * Subtracts the given value from the current number.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function subtract($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $sum = bcsub($this->value, $number->get(), $scale);

        return $this->init($sum);
    }

    /**
     * Alias for subtract method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function sub($value, int $scale = null): self
    {
        return $this->subtract($value, $scale);
    }

    /**
     * Alias for subtract method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function minus($value, int $scale = null): self
    {
        return $this->subtract($value, $scale);
    }

    /**
     * Divides the current number by the given value.
     * A fallback number can be given to make sure you can continue chaining.
     *
     * Example:
     * ```
     *   $divided = (new Number('200.000'))->divide($value, null, '0.0000')
     * ```
     *
     * @param AbstractNumber|string|float|int $value
     * @param AbstractNumber|string|float|int|null $fallback
     */
    public function divide($value, int $scale = null, $fallback = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        if ($number->isZero()) {
            if ($fallback === null) {
                throw new DivisionByZeroError();
            }

            return $this->init((string) $fallback);
        }

        $div = bcdiv($this->value, $number->get(), $scale);

        return $this->init($div);
    }

    /**
     * Alias for divide method.
     *
     * @param AbstractNumber|string|float|int $value
     * @param AbstractNumber|string|float|int|null $fallback
     */
    public function div($value, int $scale = null, $fallback = null): self
    {
        return $this->divide($value, $scale, $fallback);
    }

    /**
     * Multiplies the current value by the given number.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function multiply($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $mul = bcmul($this->value, $number->get(), $scale);

        return $this->init($mul);
    }

    /**
     * Alias for multiply method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function mul($value, int $scale = null): self
    {
        return $this->multiply($value, $scale);
    }

    /**
     * Get modulus of the given value based on the current number.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function modulus($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $mod = bcmod($this->value, $number->get(), $scale);

        return $this->init($mod);
    }

    /**
     * Alias for modulus method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function mod($value, int $scale = null): self
    {
        return $this->modulus($value, $scale);
    }

    /**
     * Raises the current number to the power of the given exponent.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function pow($value, int $scale = null): self
    {
        $exponent = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $exponentWithZeroScale = $exponent->toString(0);
        if ($exponent->isEqual($exponentWithZeroScale) === false) {
            throw new DecimalExponentError();
        }

        $mod = bcpow($this->value, $exponentWithZeroScale, $scale);

        return $this->init($mod);
    }

    /**
     * Raise an arbitrary precision number to another, reduced by a specified modulus.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function powmod($value, $modulus, int $scale = null): self
    {
        $exponent = $this->getNumberFromInput($value);
        $modulus = $this->getNumberFromInput($modulus);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $exponentWithZeroScale = $exponent->toString(0);
        if ($exponent->isEqual($exponentWithZeroScale) === false) {
            throw new DecimalExponentError();
        }

        $powmod = bcpowmod($this->value, $exponentWithZeroScale, $modulus->toString(0), $scale);

        return $this->init($powmod);
    }

    /**
     * Get the square root of an arbitrary precision number.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function sqrt(int $scale = null): self
    {
        $scale = $scale ?? self::INTERNAL_SCALE;

        $mod = bcsqrt($this->value, $scale);

        return $this->init($mod);
    }

    /**
     * Alias for sqrt method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function squareRoot(int $scale = null): self
    {
        return $this->sqrt($scale);
    }

    /**
     * Return boolean if the current value is a positive number.
     */
    public function isPositive(): bool
    {
        return bccomp($this->value, '0') === 1;
    }

    /**
     * Return boolean if the current value is a positive number.
     */
    public function isNegative(): bool
    {
        return bccomp($this->value, '0') === -1;
    }

    /**
     * Returns boolean if the current value is zero "0".
     */
    public function isZero(): bool
    {
        return $this->isEqual('0');
    }

    /**
     * Returns boolean if the current value is thirteen "13".
     */
    public function isThirteen(): bool
    {
        return $this->isEqual('13');
    }

    /**
     * Returns boolean if the current value is greater than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isGreaterThan($value, int $scale = null): bool
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        return bccomp($this->value, $number->get(), $scale) === 1;
    }

    /**
     * Returns boolean if the current value is less than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isLessThan($value, int $scale = null): bool
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        return bccomp($this->value, $number->get(), $scale) === -1;
    }

    /**
     * Returns boolean if the current value is equal to the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isEqual($value, int $scale = null): bool
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        return bccomp($this->value, $number->get(), $scale) === 0;
    }

    /**
     * Rounds the current number, with a given precision (default 0).
     *
     * @param int $precision
     */
    public function round(int $precision = 0, int $mode = self::ROUND_HALF_UP): self
    {
        if (in_array($mode, self::ROUNDING_MODES) === false) {
            throw new InvalidRoundingModeException();
        }

        return $this->init((string) round((float) $this->value, $precision, $mode));
    }

    /**
     * Ceils the current number.
     */
    public function ceil(): self
    {
        return $this->init((string) ceil((float) $this->value));
    }

    /**
     * Floors the current number.
     */
    public function floor(): self
    {
        return $this->init((string) floor((float) $this->value));
    }

    /**
     * Returns it's parent by which this instance was initialized.
     */
    public function parent(): ?self
    {
        return $this->parent;
    }

    /**
     * Converts the current Number instance into a string.
     */
    public function toString(int $scale = null): string
    {
        $scale = $scale ?? self::DEFAULT_SCALE;

        return bcadd('0.0000', $this->value, $scale);
    }

    /**
     * Converts the current Number instance into a string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @internal Provides value with internal scale.
     */
    protected function get(): string
    {
        return $this->toString(self::INTERNAL_SCALE);
    }

    /**
     * @internal Provides an instance of Number based on the input. Supports multiple input data types.
     *
     * @param AbstractNumber|string|float|int $value
     */
    protected function getNumberFromInput($value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_string($value) || is_float($value) || is_int($value)) {
            return $this->init((string) $value);
        }

        throw new InvalidNumberInputTypeException($value);
    }
}
