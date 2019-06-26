<?php

namespace App\Exceptions;

use Exception;

class ParseException extends Exception
{
    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = 'Could not parse the HTML.';
}
