<?php

declare(strict_types=1);

namespace Madebybob\Number;

use Madebybob\Number\Formatter\FormatterInterface;
use Madebybob\Number\Formatter\NumberFormatter;

class Number extends AbstractNumber
{
    public function formatter(): FormatterInterface
    {
        return new NumberFormatter();
    }
}
