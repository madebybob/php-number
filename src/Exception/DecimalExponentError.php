<?php

declare(strict_types=1);

namespace Number\Exception;

use ArithmeticError;

class DecimalExponentError extends ArithmeticError
{
    public function __construct()
    {
        parent::__construct('Decimal given as exponent', 500);
    }
}
