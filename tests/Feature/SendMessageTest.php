<?php

namespace Tests\Feature;

use App\ICommands\SendMessage;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with("https://api.telegram.org/bot123/sendMessage",
                ["form_params" => ["chat_id" => 321, "text" => "message", "parse_mode" => "html"]]
            )
            ->andReturn(Mockery::mock(ResponseInterface::class));
        $sendMessage = new SendMessage(321, "message", "123", $client);
        $result = $sendMessage->execute();
        $this->assertTrue($result->isSuccess());
    }

    public function testExecuteException(): void
    {
        $client = Mockery::mock(Client::class);
        $errorMessage = 'Error in Api';
        $client->shouldReceive('post')->once()->andThrow(new Exception($errorMessage));
        $sendMessage = new SendMessage(321, "message", "123", $client);
        $result = $sendMessage->execute();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals($errorMessage, $result->getMessage());
    }
}
