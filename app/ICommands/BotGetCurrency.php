<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Jobs\ProcessBotResponse;
use App\Models\Currency;
use App\Models\TelegramMessage;
use App\Models\TelegramNextCommand;

/**
 * Checks the entered currency and calls and saves the next command that will need to be executed when receiving
 * the next data
 */
class BotGetCurrency implements ICommand
{
    public function __construct(
        private readonly TelegramMessage $telegramMessage,
        private readonly string $nextCommandClass,
        private readonly string $attribute,
        private string $text,
    ){}
    public static function create(
        TelegramMessage $telegramMessage,
        string $nextCommandClass,
        string $attribute,
        $text,
    ): self
    {
        return new self($telegramMessage, $nextCommandClass, $attribute, $text);
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        $currency = Currency::query()->where('char_code', $this->telegramMessage->text)->first();
        if ($currency) {
            /** @var  TelegramNextCommand $nextCommand */
            $nextCommand = $this->telegramMessage->telegramNextCommand;
            $nextCommand->update([
                'command' => $this->nextCommandClass,
                'properties' => array_merge($nextCommand->properties, [$this->attribute => $currency->id]),
            ]);
        } else {
            $this->text = "Неправильный формат валюты. Введите валюту в формате USD, EUR, RUB и т.д.";
        }
        ProcessBotResponse::dispatch($this->telegramMessage->telegramChat->id, $this->text);
        return new CommandResult(true);
    }
}
