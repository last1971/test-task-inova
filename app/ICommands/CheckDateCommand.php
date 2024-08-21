<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Models\TelegramMessage;

/**
 * Check that properties is set
 */
class CheckDateCommand implements ICommand
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
        return new CommandResult(true);
    }
}
