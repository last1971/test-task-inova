<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBotResponse implements ShouldQueue
{
    use Queueable;

    private string $token;
    /**
     * Create a new job instance.
     * @param int $chatId
     * @param string $message
     */
    public function __construct(private readonly int $chatId, private readonly string $message)
    {
        $this->token = env('BOT_TOKEN');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
        $client = new Client();
        $res = $client->post(
            "https://api.telegram.org/bot{$this->token}/sendMessage",
            ["form_params" => ["chat_id" => $this->chatId, "text" => $this->message, "parse_mode" => "html"]],
        );
        } catch (Exception $e) {

        }
    }
}
