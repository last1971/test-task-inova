<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\Currency;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;
/**
 * Class for checking Target currency
 */
class BotGetTargetCurrency implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        return BotGetCurrency::create(
            $this->telegramMessage,
            BotConvertCommand::class, // попроавить
            'to_currency_id',
            'Введите дату (в формате ДД.ММ.ГГГГ):'
        )->execute();
    }
}
