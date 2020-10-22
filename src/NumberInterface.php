<?php

declare(strict_types=1);

namespace Madebybob\Number;

interface NumberInterface
{
    /**
     * Returns a new instance of the class which extends AbstractNumber::class.
     */
    public function init(string $value): AbstractNumber;
}
