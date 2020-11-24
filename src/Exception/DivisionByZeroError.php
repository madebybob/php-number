<?php

declare(strict_types=1);

namespace Number\Exception;

class DivisionByZeroError extends \DivisionByZeroError
{
    public function __construct()
    {
        parent::__construct('Division by zero');
    }
}
