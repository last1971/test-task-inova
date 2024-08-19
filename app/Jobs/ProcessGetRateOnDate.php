<?php

namespace App\Jobs;

use App\ICommands\GetRatesFromDB;
use App\Models\TelegramMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessGetRateOnDate implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly int $id)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $telegramMessage = TelegramMessage::query()
            ->with('telegramChat')->find($this->id);
        $getRatesFromDB = new GetRatesFromDB($telegramMessage);
        $res = $getRatesFromDB->execute();
        ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
    }
}
