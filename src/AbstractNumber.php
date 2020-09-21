<?php

declare(strict_types=1);

namespace Madebybob\Number;

use Madebybob\Number\Exception\InvalidNumberInputTypeException;

abstract class AbstractNumber implements NumberInterface
{
    private const INTERNAL_SCALE = 12;
    private const DEFAULT_SCALE = 4;

    private string $value;
    private ?self $parent;

    public function __construct($value, ?self $parent = null)
    {
        $this->value = (string) $value;

        $this->parent = $parent;
    }

    /**
     * Adds the given value to the current number.
     *
     * @param Number|string|float|int $value
     */
    public function add($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $sum = bcadd($this->value, $number->toString(), $scale);

        return $this->init($sum);
    }

    /**
     * Subtracts the given value from the current number.
     *
     * @param Number|string|float|int $value
     */
    public function subtract($value, int $scale = null): self
    {
        $number = $this->getNumberFromInput($value);
        $scale = $scale ?? self::INTERNAL_SCALE;

        $sum = bcsub($this->value, $number->toString(), $scale);

        return $this->init($sum);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string|float|int $value
     */
    public function sub($value, int $scale = null): self
    {
        return $this->subtract($value, $scale);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string|float|int $value
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
     * @param Number|string|float|int $value
     * @param Number|string|float|int $fallback
     */
    public function divide($value, int $scale = null, $fallback = null): self
    {
        $number = $this->getNumberFromInput($value);
        $fallback = $fallback ? $this->getNumberFromInput($fallback) : false;
        $scale = $scale ?? self::INTERNAL_SCALE;

        if ($number->isZero()) {
            if ($fallback) {
                return $this->init($fallback->toString());
            } else {
                throw new InvalidNumberInputTypeException($value);
            }
        }

        $div = bcdiv($this->value, $number->toString(), $scale);
        return $this->init($div);
    }

    /**
     * Alias for divide method.
     *
     * @param Number|string|float|int $value
     * @param Number|string|float|int $fallback
     */
    public function div($value, int $scale = null, $fallback = null): self
    {
        return $this->divide($value, $scale, $fallback);
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
        return bccomp($this->value, '0', self::INTERNAL_SCALE) === 0;
    }

    /**
     * Returns boolean if the current value is thirteen "13".
     */
    public function isThirteen(): bool
    {
        return bccomp($this->value, '13', 20) === 0;
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
     * Formats the number using the Formatter.
     */
    public function format(): string
    {
        return $this->formatter()->format($this);
    }

    /**
     * @internal Provides an instance of Number based on the input. Supports multiple input data types.
     * @param Number|string|float|int $value
     */
    private function getNumberFromInput($value): self
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
