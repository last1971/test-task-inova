<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;

readonly class BotStartCommand implements ICommand
{
    public function __construct(private string $chatId)
    {
    }

    public function execute(): CommandResult
    {
        // Правильно получение сообщение вынести в отдельный класс
        ProcessBotResponse::dispatch($this->chatId, 'Я могу конвертировать валюты на заданную дату. ' .
            'Наберите /help что бы получить перечень комманд');
        return new CommandResult(true);
    }
}
