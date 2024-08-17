<?php

namespace App\Jobs;

use App\Http\Requests\BotRequest;
use App\ICommands\BotHelpCommand;
use App\ICommands\BotStartCommand;
use App\ICommands\CreateTelegramMessage;
use App\Interfaces\ICommand;
use App\Models\TelegramMessage;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ProcessBotRequest implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120;
    public int $tries = 5;

    /**
     * @var ICommand[]
     */
    private array $botCommands;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly int $id)
    {
        $this->botCommands['/help'] = BotHelpCommand::class;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $telegramMessage = TelegramMessage::query()->with('telegramChat')->find($this->id);
       $commandClass = $this->botCommands[$telegramMessage->text] ?? BotStartCommand::class;
       $command = new $commandClass($telegramMessage);
       $command->execute();
    }
}
