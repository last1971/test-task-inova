<?php

namespace Tests\Feature;

use App\ICommands\RateLimitCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class RateLimitCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testSuccess(): void
    {
        $cache = Mockery::mock('alias:Illuminate\Support\Facades\Cache');
        $cache->shouldReceive('get')
            ->once()
            ->with('test', 0)
            ->andReturn(0);
        $cache->shouldReceive('put')
            ->once()
            ->with('test', 1, 60);
        $command = new RateLimitCommand('test', 'Test message', 2, 60);
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
        $cache->shouldReceive('get')
            ->once()
            ->with('test', 0)
            ->andReturn(1);
        $cache->shouldReceive('increment')
            ->once()
            ->with('test');
        $res = $command->execute();
        $this->assertTrue($res->isSuccess());
    }

    public function testFail(): void
    {
        $cache = Mockery::mock('alias:Illuminate\Support\Facades\Cache');
        $cache->shouldReceive('get')
            ->once()
            ->with('test', 0)
            ->andReturn(1);
        $cache->shouldNotHaveBeenCalled(['put', 'increment']);
        $command = new RateLimitCommand('test', 'Test message', 1, 60);
        $res = $command->execute();
        $this->assertFalse($res->isSuccess());
        $this->assertEquals('Test message', $res->getMessage());
    }
}
