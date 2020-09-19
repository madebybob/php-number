<?php

declare(strict_types=1);

namespace Madebybob\Number\Formatter;

use Madebybob\Number\AbstractNumber;

class Money extends AbstractNumber
{
    public function formatter(): FormatterInterface
    {
        return new MoneyFormatter();
    }
}
