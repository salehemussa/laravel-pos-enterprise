<?php

namespace App\Modules\Auth\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct(
        string $message = 'Invalid email or password.',
        int $code = 401
    ) {
        parent::__construct($message, $code);
    }
}