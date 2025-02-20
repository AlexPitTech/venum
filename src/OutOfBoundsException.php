<?php

namespace PitTech\vEnum;

class OutOfBoundsException extends \OutOfBoundsException
{
    public function __construct(
        string $message,
        public readonly mixed $data,
        $code = 0,
        \Exception $previous = null
    ){
        parent::__construct($message, $code, $previous);
    }
}