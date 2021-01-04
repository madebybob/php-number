<?php

declare(strict_types=1);

namespace Number\Tests\TestClasses;

use Number\AbstractNumber;
use Number\Formatter\Formatter;

class Money extends AbstractNumber
{
    /**
     * @param string|float|int $value
     */
    public static function create($value): self
    {
        return new static($value);
    }

    public function format(string $isoCode): string
    {
        return Formatter::formatMoney($this->get(), $isoCode);
    }
}
