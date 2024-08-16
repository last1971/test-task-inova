<?php

namespace App\ICommands;

use App\Helpers\CommandResult;
use App\Interfaces\ICommand;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SendMessage implements ICommand
{
    /**
     * @param int $chatId
     * @param string $message
     * @param string $token
     * @param Client $client
     */
    public function __construct(
        private readonly int $chatId,
        private readonly string $message,
        private readonly string $token,
        private readonly Client $client
    )
    {
    }

    /**
     * @param int $chatId
     * @param string $message
     * @return self
     */
    public static function create(int $chatId, string $message): self
    {
        $client = new Client();
        $token = env('BOT_TOKEN', '');
        return new self($chatId, $message, $token, $client);
    }

    /**
     * @return CommandResult
     * @throws GuzzleException
     */
    public function execute(): CommandResult
    {
        try {
            $res = $this->client->post(
                "https://api.telegram.org/bot{$this->token}/sendMessage",
                ["form_params" => ["chat_id" => $this->chatId, "text" => $this->message, "parse_mode" => "html"]],
            );
            return new CommandResult(true);
        } catch (Exception $e) {
            return new CommandResult(false, $e->getMessage());
        }
    }
}
