<?php

namespace Tests\Feature;

use App\ICommands\GetRatesOnDate;
use App\Helpers\XmlLoader;
use Carbon\Carbon;
use Exception;
use Mockery;
use Tests\TestCase;

class GetRatesOnDateTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        // Создаем мок для XmlLoader
        $xmlLoaderMock = Mockery::mock(XmlLoader::class);
        $xmlLoaderMock->shouldReceive('load')
            ->once()
            ->with('https://cbr.ru/scripts/XML_daily_eng.asp?date_req=2024-01-01')
            ->andReturn(simplexml_load_string('
                <ValCurs Date="01.01.2024" name="Foreign Currency Market">
                    <Valute ID="R01235">
                        <NumCode>036</NumCode>
                        <CharCode>AUD</CharCode>
                        <Name>Australian Dollar</Name>
                        <VunitRate>1</VunitRate>
                    </Valute>
                    <Valute ID="R01239">
                        <NumCode>124</NumCode>
                        <CharCode>CAD</CharCode>
                        <Name>Canadian Dollar</Name>
                        <VunitRate>2</VunitRate>
                    </Valute>
                </ValCurs>
            '));
        $currencyMock = Mockery::mock('alias:App\Models\Currency');
        $currencyMock->id = 'test';
        $currencyMock->shouldReceive('query->createOrFirst')
            ->once()
            ->with([
                'id' => 'R01235',
                'num_code' => '036',
                'char_code' => 'AUD',
                'name' => 'Australian Dollar',
            ])
            ->andReturn($currencyMock);
        $currencyMock->shouldReceive('query->createOrFirst')
            ->once()
            ->with([
                'id' => 'R01239',
                'num_code' => '124',
                'char_code' => 'CAD',
                'name' => 'Canadian Dollar',
            ])
            ->andReturn($currencyMock);
        $currencyMock->shouldReceive('query->createOrFirst')
            ->once()
            ->with([
                'id' => 'R01000',
                'num_code' => '643',
                'char_code' => 'RUB',
                'name' => 'Russian rouble',
            ])
            ->andReturn($currencyMock);
        $exchangeRateMock = Mockery::mock('alias:App\Models\ExchangeRate');
        $exchangeRateMock->shouldReceive('query->updateOrCreate')
            ->once()
            ->with([
                'currency_id' => 'test',
                'date' => Carbon::parse('2024-01-01')->format('Y-m-d'),
            ], [
                'rate' => 1
            ])
            ->andReturn($exchangeRateMock);
        $exchangeRateMock->shouldReceive('query->updateOrCreate')
            ->once()
            ->with([
                'currency_id' => 'test',
                'date' => Carbon::parse('2024-01-01')->format('Y-m-d'),
            ], [
                'rate' => 2
            ])
            ->andReturn($exchangeRateMock);
        $exchangeRateMock->shouldReceive('query->updateOrCreate')
            ->once()
            ->with([
                'currency_id' => 'R01000',
                'date' => Carbon::parse('2024-01-01')->format('Y-m-d'),
            ], [
                'rate' => 1
            ])
            ->andReturn($exchangeRateMock);
        $getRatesOnDate = new GetRatesOnDate('2024-01-01', $xmlLoaderMock);
        $result = $getRatesOnDate->execute();
        $this->assertTrue($result->isSuccess());
    }

    public function testExecuteException()
    {
        // Создаем мок для XmlLoader
        $xmlLoaderMock = Mockery::mock(XmlLoader::class);
        $xmlLoaderMock->shouldReceive('load')
            ->once()
            ->with('https://cbr.ru/scripts/XML_daily_eng.asp?date_req=2024-01-01')
            ->andThrow(new Exception('Error loading XML'));
        $getRatesOnDate = new GetRatesOnDate('2024-01-01', $xmlLoaderMock);
        $result = $getRatesOnDate->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals(
            'Сервис получения курсов временно не доступен, попробуйте позже',
            $result->getMessage()
        );
    }
}
