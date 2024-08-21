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
        $rateLimit = new RateLimitCommand(
            'rate-limit-' . $this->telegramMessage->telegramChat->id . '-' . $this->telegramMessage->telegramUser->id,
            'Вы перевысили количество запросов  в минуту',
            env('BOT_LIMIT', 10),
            env('BOT_INTERVAL', 60),
        );
        $command = new InterruptionChainCommand(
            [$rateLimit, new StartConvertCommand($this->telegramMessage)], [false, true]
        );
        $res = $command->execute();
        ProcessBotResponse::dispatch($this->telegramMessage->telegramChat->id, $res->getMessage());
        return new CommandResult(true);
    }
}
