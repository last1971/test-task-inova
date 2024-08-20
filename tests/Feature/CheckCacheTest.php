<?php

namespace Tests\Feature;

use App\ICommands\CheckCache;
use App\Models\TelegramMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Mockery;
use stdClass;
use Tests\TestCase;

class CheckCacheTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $cache = Mockery::mock('alias:Illuminate\Support\Facades\Cache');
        $cache->shouldReceive('get')
            ->once()
            ->with('date=01.01.2002;from_currency_id=USD;to_currency_id=RUB')
            ->andReturn('test');
        $telegramMessage = Mockery::mock('alias:App\Models\TelegramMessage');
        $telegramMessage->telegramNextCommand = new stdClass();
        $telegramMessage->telegramNextCommand->properties = [
            'date' => '01.01.2002',
            'from_currency_id' =>'USD',
            'to_currency_id' => 'RUB',
        ];
        $command = new CheckCache($telegramMessage);
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
        $this->assertEquals('test', $res->getMessage());
    }

}
