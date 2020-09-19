<?php

declare(strict_types=1);

namespace Madebybob\Number;

use Madebybob\Number\Formatter\FormatterInterface;

interface NumberInterface
{
    public function init(string $value, bool $isParent = true): AbstractNumber;
    public function formatter(): FormatterInterface;
}
