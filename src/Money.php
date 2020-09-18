<?php

declare(strict_types=1);

namespace Madebybob\Number\Formatter;

use Madebybob\Number\Number;

class Money extends Number
{
    public function __construct($value = null)
    {
        parent::__construct($value, new MoneyFormatter());
    }
}
