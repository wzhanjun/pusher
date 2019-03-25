<?php

namespace Wzhanjun\Push\Exceptions;

class RequestErrorException extends Exception
{
    public $raw = [];

    public function __construct(string $message = "", int $code = 0, $raw = [])
    {
        parent::__construct($message, $code);

        $this->raw = $raw;
    }

}