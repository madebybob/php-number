<?php

declare(strict_types=1);

namespace Madebybob\Number;

use Madebybob\Number\Exception\InvalidNumberInputTypeException;

abstract class AbstractNumber implements NumberInterface
{
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
     * @param int $scale default 4
     */
    public function add($value, $scale = 4): self
    {
        $number = $this->getNumberFromInput($value);

        $sum = bcadd($this->value, $number->toString(), $scale);

        return $this->init($sum);
    }

    /**
     * Subtracts the given value from the current number.
     *
     * @param Number|string|float|int $value
     * @param int $scale default 4
     */
    public function subtract($value, $scale = 4): self
    {
        $number = $this->getNumberFromInput($value);

        $sum = bcsub($this->value, $number->toString(), $scale);

        return $this->init($sum);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string|float|int $value
     * @param int $scale default 4
     */
    public function sub($value, $scale = 4): self
    {
        return $this->subtract($value, $scale);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string|float|int $value
     * @param int $scale default 4
     */
    public function minus($value, $scale = 4): self
    {
        return $this->subtract($value, $scale);
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
        return bccomp($this->value, '0', 20) === 0;
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
     *
     * @param int $scale default 4
     */
    public function toString($scale = 4): string
    {
        return bcadd('0.000', $this->value, $scale);
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
            return $this->init((string) $value, false);
        }

        throw new InvalidNumberInputTypeException($value);
    }
}
