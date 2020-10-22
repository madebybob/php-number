<?php

declare(strict_types=1);

namespace Madebybob\Number;

use Madebybob\Number\Formatter\FormatterInterface;
use Madebybob\Number\Formatter\MoneyFormatter;

class Money extends AbstractNumber
{
    private string $isoCode;

    public function __construct($value, string $isoCode, ?AbstractNumber $parent = null)
    {
        parent::__construct($value, $parent);
        $this->isoCode = $isoCode;
    }

    public function isoCode(): string
    {
        return $this->isoCode;
    }

    public function init(string $value): self
    {
        return new self($value, $this->isoCode, $this);
    }

    public function formatter(): FormatterInterface
    {
        return new MoneyFormatter();
    }
}
