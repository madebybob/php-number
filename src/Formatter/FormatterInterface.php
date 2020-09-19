<?php

declare(strict_types=1);

namespace Madebybob\Number\Formatter;

use Madebybob\Number\AbstractNumber;

interface FormatterInterface
{
    public function format(AbstractNumber $number): string;
}
