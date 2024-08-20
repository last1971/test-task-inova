<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\TelegramMessage;

class GetRatesCommand implements ICommand
{
    /**
     * @var ICommand[]
     */
    private array $commands;
    /**
     * @var bool
     */
    private array $results;
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
        $getRatesFromDB = new GetRatesFromDB($this->telegramMessage);
        $this->commands = [
            new CheckDateCommand($this->telegramMessage),
            new CheckCache($this->telegramMessage),
            $getRatesFromDB,
            GetRatesOnDate::create($this->telegramMessage->text),
            $getRatesFromDB,
        ];
        $this->results = [
            false,
            true,
            true,
            false,
            true,
        ];
    }

    public function execute(): CommandResult
    {
        foreach ($this->commands as $index => $command) {
            $res = $command->execute();
            if ($res->isSuccess() === $this->results[$index] || $index + 1 === count($this->results)) {
                ProcessBotResponse::dispatch($this->telegramMessage->telegramChat->id, $res->getMessage());
                return new CommandResult(true, $res->getMessage());
            }
        }
    }
}
