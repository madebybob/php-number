<?php

declare(strict_types=1);

namespace Number\Exception;

use InvalidArgumentException;

class InvalidRoundingModeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid rounding mode given.');
    }
}
