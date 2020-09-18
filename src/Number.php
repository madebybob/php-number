<?php

declare(strict_types=1);

namespace Madebybob\Number;

class Number
{
    /**
     * @var string
     */
    private $originalValue = null;

    /**
     * @var string
     */
    private $value = null;

    /**
     * @var Formatter
     */
    private $formatter = null;

    public function __construct($value = null)
    {
        $this->formatter = new Formatter();

        if ($value) {
            $this->originalValue = (string) $value;
            $this->value = (string) $value;
        }
    }

    /**
     * Adds the given value to the current number.
     *
     * @param Number|string $value
     * @param int $scale default 4
     */
    public function add($value, $scale = 4): self
    {
        if (is_string($value)) {
            $value = new Number($value);
        }

        $sum = bcadd($this->value, $value->toString(), $scale);

        return new Number($sum);
    }

    /**
     * Subtracts the given value from the current number.
     *
     * @param Number|string $value
     * @param int $scale default 4
     */
    public function subtract($value, $scale = 4): self
    {
        if (is_string($value)) {
            $value = new Number($value);
        }

        $sum = bcsub($this->value, $value->toString(), $scale);

        return new Number($sum);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string $value
     * @param int $scale default 4
     */
    public function sub($value, $scale = 4): self
    {
        return $this->subtract($value, $scale);
    }

    /**
     * Alias for subtract method.
     *
     * @param Number|string $value
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
        return bccomp($this->value, "0") === 1;
    }

    /**
     * Return boolean if the current value is a positive number.
     */
    public function isNegative(): bool
    {
        return bccomp($this->value, "0") === -1;
    }

    /**
     * Returns boolean if the current value is zero "0".
     */
    public function isZero(): bool
    {
        return bccomp($this->value, "0") === 0;
    }

    /**
     * Converts the current Number instance into a string.
     *
     * @param int $scale default 4
     */
    public function toString($scale = 4): string
    {
        return bcadd("0.000", $this->value, $scale);
    }
}
