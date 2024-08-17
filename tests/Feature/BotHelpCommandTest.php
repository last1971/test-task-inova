<?php

namespace Tests\Feature;

use App\ICommands\BotHelpCommand;
use App\Jobs\ProcessBotResponse;
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
        $command = new BotHelpCommand(1);
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
    }

}
