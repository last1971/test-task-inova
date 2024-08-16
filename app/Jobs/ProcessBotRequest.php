<?php

namespace App\Jobs;

use App\Http\Requests\BotRequest;
use App\ICommands\BotHelpCommand;
use App\ICommands\BotStartCommand;
use App\Interfaces\ICommand;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ProcessBotRequest implements ShouldQueue
{
    use Queueable;

    /**
     * @var ICommand[]
     */
    private array $botCommands;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $botRequest)
    {
        $this->botCommands['/help'] = BotHelpCommand::class;
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->botRequest['message']['chat']['id']))
            ->expireAfter(env('BOT_PROCESS_EXPIRE', 180))];
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $commandClass = $this->botCommands[$this->botRequest['message']['text']] ??  BotStartCommand::class;
        $command = new $commandClass($this->botRequest['message']['chat']['id']);
        $command->execute();
    }
}
