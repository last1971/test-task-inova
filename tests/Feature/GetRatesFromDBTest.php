<?php

namespace Tests\Feature;

use App\ICommands\GetRatesFromDB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use stdClass;
use Tests\TestCase;

class GetRatesFromDBTest extends TestCase
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
        $command = new GetRatesFromDB($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }

    public function testHasNotProperties(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $command = new GetRatesFromDB($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }

    public function testHasNotDate(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $telegramMessage->telegramNextCommand->properties = ['from_currency_id' => 1, 'to_currency_id' => 2];
        $command = new GetRatesFromDB($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Не предвиденная ошибка', $result->getMessage());
    }

    public function testHasNotToCurrency(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $telegramMessage->telegramNextCommand->properties = [
            'from_currency_id' => '1', 'to_currency_id' => '2', 'date' => '2020-01-11'
        ];
        $exchangeRateMock = Mockery::mock('alias:App\Models\ExchangeRate');

        $exchangeRateMock->shouldReceive('query')
            ->once()
            ->andReturn($exchangeRateMock); // Возвращаем сам объект для следующего вызова

        $exchangeRateMock->shouldReceive('firstWhere')
            ->once()
            ->with([
                'currency_id' => 1,
                'date' => '2020-01-11',
            ])
            ->andReturn($exchangeRateMock);

        $exchangeRateMock->shouldReceive('query')
            ->once()
            ->andReturn($exchangeRateMock); // Возвращаем сам объект для следующего вызова

        $exchangeRateMock->shouldReceive('firstWhere')
            ->once()
            ->with([
                'currency_id' => 2,
                'date' => '2020-01-11',
            ])
            ->andReturn(null);
        $command = new GetRatesFromDB($telegramMessage);
        $result = $command->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('На заданную дату валютная пара отсутсвует', $result->getMessage());
    }

    public function testExecuteSuccess(): void
    {
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $telegramMessage->telegramNextCommand->properties = [
            'from_currency_id' => '1', 'to_currency_id' => '2', 'date' => '2020-01-11'
        ];

        $exchangeRateMock = Mockery::mock('alias:App\Models\ExchangeRate');
        $exchangeRateMock->rate = 10;

        $exchangeRateMock->shouldReceive('query')
            ->once()
            ->andReturn($exchangeRateMock); // Возвращаем сам объект для следующего вызова

        $exchangeRateMock->shouldReceive('firstWhere')
            ->once()
            ->with([
                'currency_id' => 1,
                'date' => '2020-01-11',
            ])
            ->andReturn($exchangeRateMock);

        $exchangeRateMock->shouldReceive('query')
            ->once()
            ->andReturn($exchangeRateMock); // Возвращаем сам объект для следующего вызова

        $exchangeRateMock->shouldReceive('firstWhere')
            ->once()
            ->with([
                'currency_id' => 2,
                'date' => '2020-01-11',
            ])
            ->andReturn($exchangeRateMock);
        $command = new GetRatesFromDB($telegramMessage);
        $result = $command->execute();
        $this->assertTrue($result->isSuccess());
        $this->assertEquals('Курс: 1.000000', $result->getMessage());
    }
}
