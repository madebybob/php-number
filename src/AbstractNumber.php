<?php

declare(strict_types=1);

namespace MadeByBob\Number;

use MadeByBob\Number\Exception\DecimalExponentError;
use MadeByBob\Number\Exception\DivisionByZeroError;
use MadeByBob\Number\Exception\InvalidNumberInputTypeException;
use MadeByBob\Number\Exception\InvalidRoundingModeException;
use WeakReference;

abstract class AbstractNumber implements \JsonSerializable
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

    /**
     * Weak reference to the instance this instance was derived from.
     *
     * The reference is weak on purpose: a strong reference would keep every
     * intermediate result of a calculation alive for as long as its last
     * descendant lives, which makes accumulating loops grow without bound.
     *
     * @var WeakReference<AbstractNumber>|null
     */
    protected ?WeakReference $parent;

    /**
     * Cached weak reference to $this, shared with every derived instance.
     *
     * @var WeakReference<AbstractNumber>|null
     */
    private ?WeakReference $reference = null;

    /**
     * Lazily calculated value, truncated to the internal scale.
     */
    private ?string $internal = null;

    /**
     * @param string|float|int $value
     */
    public function __construct($value, ?self $parent = null)
    {
        if (is_string($value) || is_int($value) || is_float($value)) {
            $this->value = self::normalize((string) $value);
        } else {
            throw new InvalidNumberInputTypeException($value);
        }

        $this->parent = $parent === null ? null : $parent->reference();
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
        $sum = bcadd($this->value, $this->getValueFromInput($value), $scale ?? self::INTERNAL_SCALE);

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
        $sum = bcsub($this->value, $this->getValueFromInput($value), $scale ?? self::INTERNAL_SCALE);

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
     *   $divided = (new MadeByBob\Number('200.000'))->divide($value, null, '0.0000')
     * ```
     *
     * @param AbstractNumber|string|float|int $value
     * @param AbstractNumber|string|float|int|null $fallback
     */
    public function divide($value, int $scale = null, $fallback = null): self
    {
        $divisor = $this->getValueFromInput($value);

        if (bccomp($divisor, '0', self::INTERNAL_SCALE) === 0) {
            if ($fallback === null) {
                throw new DivisionByZeroError();
            }

            return $this->init($this->getValueFromInput($fallback));
        }

        $div = bcdiv($this->value, $divisor, $scale ?? self::INTERNAL_SCALE);

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
        $mul = bcmul($this->value, $this->getValueFromInput($value), $scale ?? self::INTERNAL_SCALE);

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
        $mod = bcmod($this->value, $this->getValueFromInput($value), $scale ?? self::INTERNAL_SCALE);

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
        $exponent = $this->toInteger($this->getValueFromInput($value));

        $pow = bcpow($this->value, $exponent, $scale ?? self::INTERNAL_SCALE);

        return $this->init($pow);
    }

    /**
     * Raise an arbitrary precision number to another, reduced by a specified modulus.
     *
     * @param AbstractNumber|string|float|int $value
     * @param AbstractNumber|string|float|int $modulus
     */
    public function powmod($value, $modulus, int $scale = null): self
    {
        $exponent = $this->toInteger($this->getValueFromInput($value));
        $modulus = bcadd($this->getValueFromInput($modulus), '0', 0);

        $powmod = bcpowmod($this->value, $exponent, $modulus, $scale ?? self::INTERNAL_SCALE);

        return $this->init($powmod);
    }

    /**
     * Get the square root of an arbitrary precision number.
     */
    public function sqrt(int $scale = null): self
    {
        $sqrt = bcsqrt($this->value, $scale ?? self::INTERNAL_SCALE);

        return $this->init($sqrt);
    }

    /**
     * Alias for sqrt method.
     */
    public function squareRoot(int $scale = null): self
    {
        return $this->sqrt($scale);
    }

    /**
     * Get the absolute value of the current value.
     */
    public function absolute(): self
    {
        if (strncmp($this->value, '-', 1) !== 0) {
            return $this;
        }

        return $this->init(substr($this->value, 1));
    }

    /**
     * Alias for absolute method.
     */
    public function abs(): self
    {
        return $this->absolute();
    }

    /**
     * Get the opposite value of the current value.
     */
    public function opposite(): self
    {
        if (strncmp($this->value, '-', 1) === 0) {
            return $this->init(substr($this->value, 1));
        }

        return $this->init(self::sign(true, $this->value));
    }

    /**
     * Alias for opposite method.
     */
    public function opp(): self
    {
        return $this->opposite();
    }

    /**
     * Prevent the current value to be less than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function min($value = null): self
    {
        $minimum = $this->getValueFromInput($value);

        if (bccomp($this->value, $minimum, self::INTERNAL_SCALE) === -1) {
            return $this->init($minimum);
        }

        return $this;
    }

    /**
     * Prevent the current value to be more than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function max($value = null): self
    {
        $maximum = $this->getValueFromInput($value);

        if (bccomp($this->value, $maximum, self::INTERNAL_SCALE) === 1) {
            return $this->init($maximum);
        }

        return $this;
    }

    /**
     * Put a clamp on the current value.
     *
     * @param AbstractNumber|string|float|int $min
     * @param AbstractNumber|string|float|int $max
     */
    public function clamp($min, $max): self
    {
        return $this->min($min)->max($max);
    }

    /**
     * Return boolean if the current value is a positive number.
     */
    public function isPositive(): bool
    {
        return bccomp($this->value, '0', self::INTERNAL_SCALE) === 1;
    }

    /**
     * Return boolean if the current value is a positive number.
     */
    public function isNegative(): bool
    {
        return bccomp($this->value, '0', self::INTERNAL_SCALE) === -1;
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
        return bccomp($this->value, '13', self::INTERNAL_SCALE) === 0;
    }

    /**
     * Returns boolean if the current value is equal to the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isEqual($value, int $scale = null): bool
    {
        return $this->compare($value, $scale) === 0;
    }

    /**
     * Alias for isEqual method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function eq($value, int $scale = null): bool
    {
        return $this->isEqual($value, $scale);
    }

    /**
     * Returns boolean if the current value is greater than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isGreaterThan($value, int $scale = null): bool
    {
        return $this->compare($value, $scale) === 1;
    }

    /**
     * Alias for isGreaterThan method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function gt($value, int $scale = null): bool
    {
        return $this->isGreaterThan($value, $scale);
    }

    /**
     * Returns boolean if the current value is greater than or equal to the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isGreaterThanOrEqual($value, int $scale = null): bool
    {
        return $this->compare($value, $scale) >= 0;
    }

    /**
     * Alias for isGreaterThanOrEqual method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function gte($value, int $scale = null): bool
    {
        return $this->isGreaterThanOrEqual($value, $scale);
    }

    /**
     * Returns boolean if the current value is less than the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isLessThan($value, int $scale = null): bool
    {
        return $this->compare($value, $scale) === -1;
    }

    /**
     * Alias for isLessThan method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function lt($value, int $scale = null): bool
    {
        return $this->isLessThan($value, $scale);
    }

    /**
     * Returns boolean if the current value is less than or equal to the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function isLessThanOrEqual($value, int $scale = null): bool
    {
        return $this->compare($value, $scale) <= 0;
    }

    /**
     * Alias for isGreaterThanOrEqual method.
     *
     * @param AbstractNumber|string|float|int $value
     */
    public function lte($value, int $scale = null): bool
    {
        return $this->isLessThanOrEqual($value, $scale);
    }

    /**
     * Rounds the current number, with a given precision (default 0).
     */
    public function round(int $precision = 0, int $mode = self::ROUND_HALF_UP): self
    {
        if (isset(self::ROUNDING_MODES[$mode]) === false) {
            throw new InvalidRoundingModeException();
        }

        return $this->init(self::roundValue($this->value, $precision, $mode));
    }

    /**
     * Ceils the current number.
     */
    public function ceil(): self
    {
        [$negative, $integer, $fraction] = self::split($this->value);

        if ($negative === false && $fraction !== '') {
            return $this->init(bcadd($integer, '1', 0));
        }

        return $this->init(self::sign($negative, $integer));
    }

    /**
     * Floors the current number.
     */
    public function floor(): self
    {
        [$negative, $integer, $fraction] = self::split($this->value);

        if ($negative && $fraction !== '') {
            return $this->init(bcsub(self::sign(true, $integer), '1', 0));
        }

        return $this->init(self::sign($negative, $integer));
    }

    /**
     * Returns it's parent by which this instance was initialized.
     *
     * Note that the parent is referenced weakly, so `null` is returned as soon
     * as the parent has been garbage collected. Keep a reference to the numbers
     * you want to trace back to.
     */
    public function parent(): ?self
    {
        if ($this->parent === null) {
            return null;
        }

        $parent = $this->parent->get();

        return $parent instanceof self ? $parent : null;
    }

    /**
     * Converts the current MadeByBob\Number instance into a string.
     */
    public function toString(int $scale = null): string
    {
        return bcadd($this->value, '0', $scale ?? self::DEFAULT_SCALE);
    }

    /**
     * Converts the current MadeByBob\Number instance into a string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Converts the current MadeByBob\Number instance into a string.
     */
    public function jsonSerialize(): mixed
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
     * @internal Provides an instance of MadeByBob\Number based on the input. Supports multiple input data types.
     *
     * @param AbstractNumber|string|float|int $value
     */
    protected function getNumberFromInput($value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_string($value) || is_float($value) || is_int($value)) {
            return $this->init(self::normalize((string) $value));
        }

        throw new InvalidNumberInputTypeException($value);
    }

    /**
     * @internal Provides the raw value of the given input, truncated to the internal scale.
     *
     * Unlike getNumberFromInput() this does not allocate an instance for scalar
     * input, which keeps the arithmetic and comparison methods allocation free.
     *
     * @param AbstractNumber|string|float|int $value
     */
    protected function getValueFromInput($value): string
    {
        if ($value instanceof self) {
            return $value->internalValue();
        }

        if (is_string($value) || is_int($value) || is_float($value)) {
            return self::truncate(self::normalize((string) $value));
        }

        throw new InvalidNumberInputTypeException($value);
    }

    /**
     * @internal Provides the (cached) value of this instance, truncated to the internal scale.
     */
    protected function internalValue(): string
    {
        return $this->internal ??= self::truncate($this->value);
    }

    /**
     * Compares the current value with the given value.
     *
     * @param AbstractNumber|string|float|int $value
     */
    private function compare($value, int $scale = null): int
    {
        return bccomp($this->value, $this->getValueFromInput($value), $scale ?? self::INTERNAL_SCALE);
    }

    /**
     * Provides the (cached) weak reference to this instance.
     *
     * @return WeakReference<AbstractNumber>
     */
    private function reference(): WeakReference
    {
        return $this->reference ??= WeakReference::create($this);
    }

    /**
     * Provides the integer representation of the given value, or throws when the value has decimals.
     */
    private function toInteger(string $value): string
    {
        $integer = bcadd($value, '0', 0);

        if (bccomp($value, $integer, self::INTERNAL_SCALE) !== 0) {
            throw new DecimalExponentError();
        }

        return $integer;
    }

    /**
     * Truncates the given value to the internal scale.
     *
     * Values that already fit the internal scale are returned as-is; padding
     * them with zeroes does not change their value, so the bcmath call is only
     * needed to cut off surplus decimals.
     */
    private static function truncate(string $value): string
    {
        $position = strpos($value, '.');

        if ($position === false || strlen($value) - $position - 1 <= self::INTERNAL_SCALE) {
            return $value;
        }

        return bcadd($value, '0', self::INTERNAL_SCALE);
    }

    /**
     * Converts exponential notation into a string bcmath can work with.
     *
     * Casting a float to string results in an exponential notation like
     * "1.0E-5" as soon as the value is small or large enough, which every
     * bcmath function rejects. Expanding the notation is lossless.
     */
    private static function normalize(string $value): string
    {
        $exponent = strpbrk($value, 'eE');
        if ($exponent === false || is_numeric($value) === false) {
            return $value;
        }

        $mantissa = substr($value, 0, -strlen($exponent));
        $negative = strncmp($mantissa, '-', 1) === 0;

        $result = self::shift($negative ? substr($mantissa, 1) : $mantissa, (int) substr($exponent, 1));

        return self::sign($negative, $result);
    }

    /**
     * Rounds the given value with the given precision and rounding mode.
     */
    private static function roundValue(string $value, int $precision, int $mode): string
    {
        [$negative, $integer, $fraction] = self::split($value);

        if ($precision >= 0 && strlen($fraction) <= $precision) {
            return $value;
        }

        // Move the digits that have to survive the rounding in front of the decimal point.
        $digits = $integer . $fraction;
        $position = strlen($integer) + $precision;

        if ($position < 1) {
            $digits = str_repeat('0', 1 - $position) . $digits;
            $position = 1;
        } elseif ($position > strlen($digits)) {
            $digits .= str_repeat('0', $position - strlen($digits));
        }

        $kept = substr($digits, 0, $position);

        if (self::roundsUp(substr($digits, $position), $kept, $mode)) {
            $kept = bcadd($kept, '1', 0);
        }

        return self::sign($negative, self::shift($kept, -$precision));
    }

    /**
     * Determines whether the truncated part has to be rounded up.
     */
    private static function roundsUp(string $fraction, string $integer, int $mode): bool
    {
        if ($fraction === '') {
            return false;
        }

        $comparison = strcmp(substr($fraction, 0, 1), '5');

        if ($comparison !== 0) {
            return $comparison > 0;
        }

        // Exactly one half only when nothing but zeroes follow the leading five.
        if (ltrim(substr($fraction, 1), '0') !== '') {
            return true;
        }

        $odd = ((int) substr($integer, -1)) % 2 === 1;

        switch ($mode) {
            case self::ROUND_HALF_DOWN:
                return false;
            case self::ROUND_HALF_EVEN:
                return $odd;
            case self::ROUND_HALF_ODD:
                return $odd === false;
            default:
                return true;
        }
    }

    /**
     * Splits the given value into its sign, integer part and fraction.
     *
     * @return array{0: bool, 1: string, 2: string}
     */
    private static function split(string $value): array
    {
        $negative = strncmp($value, '-', 1) === 0;
        if ($negative || strncmp($value, '+', 1) === 0) {
            $value = substr($value, 1);
        }

        $position = strpos($value, '.');
        if ($position === false) {
            return [$negative, $value === '' ? '0' : $value, ''];
        }

        $integer = substr($value, 0, $position);
        $fraction = rtrim(substr($value, $position + 1), '0');

        return [$negative, $integer === '' ? '0' : $integer, $fraction];
    }

    /**
     * Moves the decimal point of the given positive value to the right.
     */
    private static function shift(string $value, int $positions): string
    {
        [, $integer, $fraction] = self::split($value);

        if ($positions === 0) {
            return $fraction === '' ? $integer : $integer . '.' . $fraction;
        }

        $digits = $integer . $fraction;
        $point = strlen($integer) + $positions;

        if ($point < 1) {
            $digits = str_repeat('0', 1 - $point) . $digits;
            $point = 1;
        } elseif ($point > strlen($digits)) {
            $digits .= str_repeat('0', $point - strlen($digits));
        }

        $fraction = rtrim(substr($digits, $point), '0');

        return $fraction === '' ? substr($digits, 0, $point) : substr($digits, 0, $point) . '.' . $fraction;
    }

    /**
     * Prefixes the given value with a minus sign, unless the value is zero.
     */
    private static function sign(bool $negative, string $value): string
    {
        if ($negative === false || ltrim($value, '0.') === '') {
            return $value;
        }

        return '-' . $value;
    }
}
