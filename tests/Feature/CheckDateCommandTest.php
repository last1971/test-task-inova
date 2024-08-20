<?php

namespace Tests\Feature;

use App\ICommands\CheckDateCommand;
use App\ICommands\GetRatesFromDB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use stdClass;
use Tests\TestCase;

class CheckDateCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHasNotTelegramNextCommand(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = null;
        $command = new CheckDateCommand($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }

    public function testHasNotProperties(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $command = new CheckDateCommand($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }

    public function testHasNotDate(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $telegramMessage->telegramNextCommand->properties = ['from_currency_id' => 1, 'to_currency_id' => 2];
        $command = new CheckDateCommand($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }
}
