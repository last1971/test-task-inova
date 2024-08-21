<?php

namespace Tests\Feature;

use App\Helpers\CommandResult;
use App\ICommands\InterruptionChainCommand;
use App\Interfaces\ICommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class InterruptionChainCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFirstLink(): void
    {
        $commandResult = new CommandResult(false, 'first');
        $first = Mockery::mock(ICommand::class);
        $first->shouldReceive('execute')->once()->andReturn($commandResult);
        $second = Mockery::mock(ICommand::class);
        $second->shouldNotHaveBeenCalled();
        $command = new InterruptionChainCommand([$first, $second], [false, true]);
        $res = $command->execute();
        $this->assertEquals($commandResult, $res);
    }

    public function testLastLink(): void
    {
        $commandResult1 = new CommandResult(true, 'first');
        $commandResult2 = new CommandResult(false, 'second');
        $first = Mockery::mock(ICommand::class);
        $first->shouldReceive('execute')->once()->andReturn($commandResult1);
        $second = Mockery::mock(ICommand::class);
        $second->shouldReceive('execute')->once()->andReturn($commandResult2);
        $command = new InterruptionChainCommand([$first, $second], [false, true]);
        $res = $command->execute();
        $this->assertEquals($commandResult2, $res);
    }
}
