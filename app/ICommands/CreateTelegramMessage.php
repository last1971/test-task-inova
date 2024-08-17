<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use App\Models\TelegramUser;
use Exception;

/**
 * Add to database received telegram message
 */
class CreateTelegramMessage implements ICommand
{
    public function __construct(private readonly array $telegramUpdate)
    {
    }

    /**
     * @param array $telegramUpdate
     * @return self
     */
    public static function create(array $telegramUpdate): self
    {
        return new self($telegramUpdate);
    }

    /**
     * @return CommandResult
     */
    public function execute(): CommandResult
    {
        try {
            $message = $this->telegramUpdate['message'];
            $user = $message['from'];
            $userId = $user['id'];
            unset($user['id']);
            $telegramUser = TelegramUser::query()->firstOrCreate(['id' => $userId], $user);
            $chat = $message['chat'];
            $chatId = $chat['id'];
            unset($chat['id']);
            $telegramChat = TelegramChat::query()->firstOrCreate(['id' => $chatId], $chat);
            unset($message['from']);
            unset($message['chat']);
            $message['telegram_chat_id'] = $chatId;
            $message['telegram_user_id'] = $userId;
            $message['id'] = $message['message_id'];
            unset($message['message_id']);
            TelegramMessage::query()->create($message);
            return new CommandResult(true);
        }
        catch (Exception $e) {
            return new CommandResult(false, $e->getMessage());
        }
    }
}
