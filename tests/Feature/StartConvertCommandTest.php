<?php

namespace Tests\Feature;

use App\ICommands\BotConvertCommand;
use App\ICommands\BotGetBaseCurrency;
use App\ICommands\StartConvertCommand;
use App\Models\TelegramMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class StartConvertCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     */
    public function testExecute(): void
    {
        $telegramNextCommand = Mockery::mock('alias:App\Models\TelegramNextCommand');
        $telegramNextCommand
            ->shouldReceive('query->updateOrCreate')
            ->once()
            ->with([
                'telegram_chat_id' => 1,
                'telegram_user_id' => 1,
            ], [
                'command' => BotGetBaseCurrency::class,
                'properties' => [],
            ]);
        $someObjectWithId = new \stdClass();
        $someObjectWithId->id = 1;
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramChat = $someObjectWithId;
        $telegramMessage->telegramUser = $someObjectWithId;
        $res = (new StartConvertCommand($telegramMessage))->execute();
        $this->assertTrue($res->isSuccess());
    }
}
