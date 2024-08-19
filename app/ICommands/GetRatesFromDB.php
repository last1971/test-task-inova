<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;

class GetRatesFromDB implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {

    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        $telegramNextCommand = $this->telegramMessage->telegramNextCommand;
        $properties = $telegramNextCommand && isset($telegramNextCommand->properties)
            ? (array) $telegramNextCommand->properties
            : [];
        if (
            !isset($properties['date'])
            ||
            !isset($properties['from_currency_id'])
            ||
            !isset($properties['to_currency_id'])
        ) {
            return new CommandResult(false, 'Не предвиденная ошибка');
        }
        $fromCurrency = ExchangeRate::query()->firstWhere([
            'currency_id' => $properties['from_currency_id'],
            'date' => $properties['date'],
        ]);
        $toCurrency = ExchangeRate::query()->firstWhere([
            'currency_id' => $properties['to_currency_id'],
            'date' => $properties['date'],
        ]);
        if (!$fromCurrency || !$toCurrency) {
            return new CommandResult(false, 'На заданную дату валютная пара отсутсвует');
        }
        return new CommandResult(
            true,
            'Курс: ' . getRate($fromCurrency->rate, $toCurrency->rate),
        );
    }
}
