<?php

declare(strict_types=1);

namespace Madebybob\Number\Formatter;

use Madebybob\Number\AbstractNumber;

class MoneyFormatter implements FormatterInterface
{
    public function format(AbstractNumber $number): string
    {
        return $number->toString();
    }
}
