<?php

namespace Tests\Feature;

use App\ICommands\BotHelpCommand;
use App\Jobs\ProcessBotResponse;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class BotHelpCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $response = Mockery::mock('alias:App\Jobs\ProcessBotResponse');
        $response->shouldReceive('dispatch')
            ->once()
            ->with(
                1,
                Mockery::on(function ($value) {
                // Проверяем, что второй аргумент является строкой и удовлетворяет нашим условиям
                    return is_string($value);
                }),
            );
        $telegramChatMock = Mockery::mock('alias:App\Models\TelegramChat');
        $telegramChatMock->id = 1;
        $telegramMessageMock = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessageMock->telegramChat = $telegramChatMock;
        $command = new BotHelpCommand($telegramMessageMock);
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
    }

}
