<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidOptionsException extends RuntimeException
{
    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = 'The given options was invalid.';
}
