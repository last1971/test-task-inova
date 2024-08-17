<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;

/**
 * Command class that runs a chain of currency conversion commands
 */
class BotConvertCommand implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        TelegramNextCommand::query()->updateOrCreate(
            [
                'telegram_chat_id' => $this->telegramMessage->telegramChat->id,
                'telegram_user_id' => $this->telegramMessage->telegramUser->id,
            ],
            [
                'command' => BotGetBaseCurrency::class,
                'properties' => [],
            ]
        );
        ProcessBotResponse::dispatch(
            $this->telegramMessage->telegramChat->id, "Введите базовую валюту (USD, EUR, RUB,  и т.д.):"
        );
        return new CommandResult(true);
    }
}
