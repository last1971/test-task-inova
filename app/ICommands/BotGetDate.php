<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Jobs\ProcessGetRateOnDate;
use App\Models\TelegramMessage;
use DateTime;

class BotGetDate implements ICommand
{

    public function __construct(private readonly TelegramMessage $telegramMessage)
    {
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        $date = DateTime::createFromFormat('d.m.Y', $this->telegramMessage->text);
        if ($date === false) {
            ProcessBotResponse::dispatch(
                $this->telegramMessage->telegramChat->id,
                'Не правильный фомат даты. Введите дату (в формате ДД.ММ.ГГГГ):'
            );
        } else {
            $nextCommand = $this->telegramMessage->telegramNextCommand;
            $nextCommand->update([
                'command' => '',
                'properties' => array_merge($nextCommand->properties, ['date' => $date->format('Y-m-d')]),
            ]);
            ProcessGetRateOnDate::dispatch($this->telegramMessage->id);
        }
        return new CommandResult(true);
    }
}
