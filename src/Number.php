<?php

declare(strict_types=1);

namespace MadeByBob\Number;

use MadeByBob\Number\Formatter\Formatter;

class Number extends AbstractNumber
{
    /**
     * @param string|float|int|null $value
     */
    public static function create($value): self
    {
        if (is_null($value)) {
            $value = '0.0000';
        }

        return new static($value);
    }

    public function format(int $minFractionDigits = 0, int $maxFractionDigits = 2): string
    {
        return Formatter::format($this->get(), $minFractionDigits, $maxFractionDigits);
    }
}
