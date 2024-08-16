<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;

class BotHelpCommand implements ICommand
{
    public function __construct(private readonly string $chatId)
    {
    }
    public function execute(): CommandResult
    {
        ProcessBotResponse::dispatch($this->chatId, "/start - Помощь\n" .
            "/help -  Перечень доступных команд \n/convert - Получить курс для валютной пары");
        return new CommandResult(true);
    }
}
