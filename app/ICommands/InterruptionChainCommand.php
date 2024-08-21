<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\TelegramMessage;

/**
 * Chain of commands with interrupt
 */
class InterruptionChainCommand implements ICommand
{
    /**
     * @param ICommand[] $commands
     * @param bool[] $results
     */
    public function __construct(
        private readonly array $commands,
        private readonly array $results,
    )
    {
        //
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        $res = null;
        foreach ($this->commands as $index => $command) {
            $res = $command->execute();
            if ($res->isSuccess() === $this->results[$index] || $index + 1 === count($this->results)) {
                break;
            }
        }
        return $res;
    }
}
