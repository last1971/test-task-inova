<?php

namespace App\Jobs;

use App\ICommands\GetRatesCommand;
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
        $telegramMessage = TelegramMessage::query()->with('telegramChat')->find($this->id);
        $command = new GetRatesCommand($telegramMessage);
        $command->execute();
        /**
        $res = $checkDate->execute();
        if (!$res->isSuccess()) {
            ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
            return;
        }
        $checkCache = new CheckCache($telegramMessage);
        $res = $checkCache->execute();
        if ($res->isSuccess()) {
            ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
            return;
        }
        $getRatesFromDB = new GetRatesFromDB($telegramMessage);
        $res = $getRatesFromDB->execute();
        if ($res->isSuccess()) {
            ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
            return;
        }
        $getRatesOnDate = GetRatesOnDate::create($telegramMessage->text);
        $res = $getRatesOnDate->execute();
        if (!$res->isSuccess()) {
            ProcessBotResponse::dispatch(
                $telegramMessage->telegramChat->id,
                'Сервис получения курсов времеено не доступен, попробуйте позже'
            );
            return;
        }
        $res = $getRatesFromDB->execute();
        ProcessBotResponse::dispatch($telegramMessage->telegramChat->id, $res->getMessage());
         */
    }
}
