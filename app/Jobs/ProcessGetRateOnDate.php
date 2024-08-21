<?php

namespace App\Jobs;

use App\ICommands\CheckCache;
use App\ICommands\CheckDateCommand;
use App\ICommands\GetRatesFromDB;
use App\ICommands\GetRatesOnDate;
use App\ICommands\InterruptionChainCommand;
use App\Models\TelegramMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Process for chain of commands to getting rate
 */
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
        $telegramMessage = TelegramMessage::query()->with('telegramChat')->find($this->id);
        $getRatesFromDB = new GetRatesFromDB($telegramMessage);
        $commands = [
            new CheckDateCommand($telegramMessage),
            new CheckCache($telegramMessage),
            $getRatesFromDB,
            GetRatesOnDate::create($telegramMessage->text),
            $getRatesFromDB,
        ];
        $results = [
            false,
            true,
            true,
            false,
            true,
        ];
        $command = new InterruptionChainCommand($commands, $results);
        $res = $command->execute();
        ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
    }
}
