<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use Illuminate\Support\Facades\Cache;

/**
 * Check rate limit command
 */
class RateLimitCommand implements ICommand
{
    public function __construct(
        private string $key,
        private string $errorMessage,
        private int $limit,
        private int $intervalInSeconds,
    )
    {
    }

    public function execute(): CommandResult
    {
        $callCount = Cache::get($this->key, 0);
        if ($callCount >= $this->limit) {
            return new CommandResult(false, $this->errorMessage); // Превышено количество вызовов
        }
        if ($callCount === 0) {
            // Устанавливаем время жизни ключа, если это первый вызов
            Cache::put($this->key, 1, $this->intervalInSeconds);
        } else {
            // Увеличиваем счётчик вызовов
            Cache::increment($this->key);
        }
        return new CommandResult(true);
    }
}
