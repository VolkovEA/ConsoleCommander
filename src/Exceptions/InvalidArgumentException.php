<?php

namespace ConsoleCommander\Exceptions;

/**
 * Class InvalidArgumentException
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, 0, null);
    }
}
