<?php

namespace App\Helpers;

/**
 *  Class that informs about the results of command execution
 */
class CommandResult
{
    public function __construct(private bool $isSuccess, private string $message = '')
    {
    }
    /**
     *  @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
