<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\Currency;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;

/**
 * Class for checking Base currency
 */
class BotGetBaseCurrency implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
    }
    public function execute(): CommandResult
    {
        return BotGetCurrency::create(
            $this->telegramMessage,
            BotGetTargetCurrency::class,
            'from_currency_id',
            'Введите целевую валюту (USD, EUR, RUB,  и т.д.):'
        )->execute();
    }
}
