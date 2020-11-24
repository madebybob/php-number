<?php

declare(strict_types=1);

namespace Number;

use Number\Formatter\Formatter;

class Number extends AbstractNumber
{
    public function init(string $value): self
    {
        return new self($value, $this);
    }

    public function format(int $minFractionDigits = 0, int $maxFractionDigits = 2): string
    {
        return Formatter::format($this->get(), $minFractionDigits, $maxFractionDigits);
    }
}
