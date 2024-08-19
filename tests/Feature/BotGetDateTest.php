<?php

namespace Tests\Feature;

use App\ICommands\BotGetDate;
use App\Jobs\ProcessGetRateOnDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use stdClass;
use Tests\TestCase;

class BotGetDateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Инициализация мока или объекта
        $this->mock = Mockery::mock('SomeClass');
        $this->mock->shouldReceive('someMethod')
            ->andReturn('default value');
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteSuccess(): void
    {
        $telegramNextCommand = Mockery::mock('alias:App\Models\TelegramNextCommand');
        $telegramNextCommand->properties = [];
        $telegramNextCommand
            ->shouldReceive('update')
            ->with(['command' => '', 'properties' => ['date' => '2011-01-11']]);

        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->text = '11.01.2011';
        $telegramMessage->id = 1;
        $telegramMessage->telegramNextCommand = $telegramNextCommand;

        $telegramChat = new stdClass();
        $telegramChat->id = 1;
        $telegramMessage->telegramChat = $telegramChat;

        $processGetRate = Mockery::mock('alias:App\Jobs\ProcessGetRateOnDate');
        $processGetRate->shouldReceive('dispatch')->once()->with(1);

        $processBotResponse = Mockery::mock('alias:App\Jobs\ProcessBotResponse');
        $processBotResponse->shouldNotHaveBeenCalled();

        $res = (new BotGetDate($telegramMessage))->execute();
        $this->assertTrue($res->isSuccess());
    }

    public function testExecuteFail(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->text = 'ABC';
        $telegramMessage->id = 1;

        $telegramChat = new stdClass();
        $telegramChat->id = 2;
        $telegramMessage->telegramChat = $telegramChat;

        $processGetRate = Mockery::mock('alias:App\Jobs\ProcessGetRateOnDate');
        $processGetRate->shouldNotHaveBeenCalled();

        $processBotResponse = Mockery::mock('alias:App\Jobs\ProcessBotResponse');
        $processBotResponse->shouldReceive('dispatch')
            ->once()
            ->with(2, 'Не правильный фомат даты. Введите дату (в формате ДД.ММ.ГГГГ):');

        $res = (new BotGetDate($telegramMessage))->execute();
        $this->assertTrue($res->isSuccess());
    }
}
