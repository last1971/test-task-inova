<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Models\TelegramMessage;
use Illuminate\Support\Facades\Cache;

class CheckCache implements ICommand
{
    public function __construct(private readonly TelegramMessage $telegramMessage)
    {

    }
    public function execute(): CommandResult
    {
        $properties = $this->telegramMessage->telegramNextCommand->properties;
        $res = Cache::get('date=' . $properties['date'] . ';from_currency_id=' . $properties['from_currency_id'] .
            ';to_currency_id=' . $properties['to_currency_id']);
        return new CommandResult(!!$res, $res ?? '');
    }
}
