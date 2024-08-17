<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\TelegramMessage;

/**
 * Process help command
 */
class BotHelpCommand implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        ProcessBotResponse::dispatch($this->telegramMessage->telegramChat->id, "/start - Помощь\n" .
            "/help -  Перечень доступных команд \n/convert - Получить курс для валютной пары");
        return new CommandResult(true);
    }
}
