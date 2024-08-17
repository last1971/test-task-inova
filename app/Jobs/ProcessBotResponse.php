<?php

namespace App\Jobs;

use App\ICommands\SendMessage;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Process send message to telegram chat
 */
class ProcessBotResponse implements ShouldQueue
{
    use Queueable;

    public int $timeout = 180;
    public int $tries = 10;
    /**
     * Create a new job instance.
     * @param int $chatId
     * @param string $message
     */
    public function __construct(private readonly int $chatId, private readonly string $message)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $sendMessage = SendMessage::create($this->chatId, $this->message);
            $res = $sendMessage->execute();
            if (!$res->isSuccess()) {
                // В случае неудачи на отправку дается 10 попыток с задержкой в 2 минуты
                $this->release(120);
            }
        } catch (Exception $e) {
            $this->release(120);
        }
    }
}
