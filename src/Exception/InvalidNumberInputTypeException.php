<?php

declare(strict_types=1);

namespace Number\Exception;

use InvalidArgumentException;

class InvalidNumberInputTypeException extends InvalidArgumentException
{
    public function __construct($value)
    {
        $message = sprintf('Invalid number input, %s given', gettype($value));

        parent::__construct($message, 500);
    }
}
