<?php

namespace App\Exceptions;

use Exception;

class CustomNotFoundException extends Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function render($request)
    {
        return response()->view('errors.404', ['message' => $this->message], 404);
    }
}
