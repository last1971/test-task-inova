<?php

namespace App\Jobs;

use App\ICommands\CreateTelegramMessage;
use App\Models\TelegramMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Process adding incoming telegram message and queuing the handler for this message
 */
class ProcessCreateTelegramMessage implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120;
    public int $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $botRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (CreateTelegramMessage::create($this->botRequest)->execute()->isSuccess()) {
            ProcessBotRequest::dispatch($this->botRequest['message']['message_id']);
        } else {
            $this->release(60);
        }
    }
}
