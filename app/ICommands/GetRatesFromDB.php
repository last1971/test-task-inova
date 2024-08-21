<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;
use Illuminate\Support\Facades\Cache;

/**
 * Get Rates from DB
 */
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
        $properties = $this->telegramMessage->telegramNextCommand->properties;
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
        $message = 'Курс: ' . getRate($fromCurrency->rate, $toCurrency->rate);
        Cache::add(
            'date=' . $properties['date'] . ';from_currency_id=' . $properties['from_currency_id'] .
            ';to_currency_id=' . $properties['to_currency_id'],
            $message
        );
        return new CommandResult(true, $message);
    }
}
