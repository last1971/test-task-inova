<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Helpers\XmlLoader;
use App\Interfaces\ICommand;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Exception;

class GetRatesOnDate implements ICommand
{
    public function __construct(private readonly string $date, private readonly XmlLoader $xmlLoader)
    {
    }

    /**
     * @param string $date
     * @return GetRatesOnDate
     */
    public static function create(string $date): GetRatesOnDate
    {
        return new GetRatesOnDate($date, new XmlLoader());
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        try {
            $url = "https://cbr.ru/scripts/XML_daily_eng.asp?date_req={$this->date}";
            $xml = $this->xmlLoader->load($url);
            foreach ($xml->Valute as $valute)
            {
                $currency = Currency::query()->createOrFirst([
                    'id' => (string)$valute['ID'],
                    'num_code' => (string)$valute->NumCode,
                    'char_code' => (string)$valute->CharCode,
                    'name' => (string)$valute->Name,
                ]);
                ExchangeRate::query()->updateOrCreate([
                    'currency_id' => $currency->id,
                    'date' => Carbon::parse($this->date)->format('Y-m-d'),
                ], [
                    'rate' => floatval(str_replace(',', '.', $valute->VunitRate)),
                ]);
            }
            return new CommandResult(true);
        } catch (Exception $e) {
            return new CommandResult(false, $e->getMessage());
        }
    }
}
