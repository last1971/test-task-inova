<?php

namespace Tests\Feature;

use App\ICommands\BotGetCurrency;
use App\Jobs\ProcessBotResponse;
use App\Models\Currency;
use App\Models\TelegramMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use stdClass;
use Tests\TestCase;

class BotGetCurrencyTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $currencyMock = Mockery::mock('alias:App\Models\Currency');
        $currencyMock->id = 1;

        $queryMock = Mockery::mock('Illuminate\Database\Eloquent\Builder');

        $currencyMock
            ->shouldReceive('query')
            ->andReturn($queryMock);

        $queryMock->shouldReceive('where')
            ->with('char_code', 'USD')
            ->andReturn($queryMock);

        $queryMock->shouldReceive('first')
            ->andReturn($currencyMock);

        $telegramNextCommand = Mockery::mock('alias:App\Models\TelegramNextCommand');
        $telegramNextCommand->properties = [];
        $telegramNextCommand
            ->shouldReceive('update')
            ->with(['command' => 'Test', 'properties' => ['test' => 1]]);

        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->text = 'USD';
        $telegramMessage->telegramNextCommand = $telegramNextCommand;

        $telegramChat = new stdClass();
        $telegramChat->id = 1;
        $telegramMessage->telegramChat = $telegramChat;

        Mockery::mock('alias:App\Jobs\ProcessBotResponse')
            ->shouldReceive('dispatch')
            ->once()
            ->with(1, 'test text');

        $command = new BotGetCurrency($telegramMessage, 'Test', 'test', 'test text');
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
    }
}
