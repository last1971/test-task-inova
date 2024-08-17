<?php

namespace Tests\Feature;

use App\ICommands\CreateTelegramMessage;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class CreateTelegramMessageTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $telegramUser = Mockery::mock("alias:App\Models\TelegramUser");
        $telegramUser->id = 1;
        $telegramUser->shouldReceive('query->firstOrCreate')
            ->once()
            ->with(['id' => '1'], [
                'first_name' => 'first',
                'last_name' => 'last',
                'username' => 'first_last',
                'language_code' => 'en',
                'is_premium' => false,
            ])
            ->andReturn($telegramUser);
        $telegramChat = Mockery::mock("alias:App\Models\TelegramChat");
        $telegramChat->id = 1;
        $telegramChat->shouldReceive('query->firstOrCreate')
            ->once()
            ->with(['id' => '1'], [
                'first_name' => 'first',
                'last_name' => 'last',
                'username' => 'first_last',
                'type' => 'private',
            ])
            ->andReturn($telegramChat);
        $telegramMessage = Mockery::mock("alias:App\Models\TelegramMessage");
        $telegramMessage->shouldReceive('query->create')
            ->once()
            ->with(['text' => 'test', 'date' => 123, 'telegram_chat_id' => 1, 'telegram_user_id' => 1, 'id' => 1])
            ->andReturnSelf();
        $res = CreateTelegramMessage::create([
            'message' => [
                'text' => 'test',
                'date' => 123,
                'message_id' => 1,
                'from' => [
                    'id' => 1,
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'username' => 'first_last',
                    'language_code' => 'en',
                    'is_premium' => false,
                ],
                'chat' => [
                    'id' => 1,
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'username' => 'first_last',
                    'type' => 'private',
                ]
            ],
        ])->execute();
        $this->assertTrue($res->isSuccess());
    }

    public function testExecuteException(): void
    {
        $telegramUser = Mockery::mock("alias:App\Models\TelegramUser");
        $telegramUser->shouldReceive('query->firstOrCreate')
            ->once()
            ->andThrowExceptions([new Exception('test exception')]);
        $res = CreateTelegramMessage::create([
            'message' => [
                'text' => 'test',
                'date' => 123,
                'message_id' => 1,
                'from' => [
                    'id' => 1,
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'username' => 'first_last',
                    'language_code' => 'en',
                    'is_premium' => false,
                ],
                'chat' => [
                    'id' => 1,
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'username' => 'first_last',
                    'type' => 'private',
                ]
            ],
        ])->execute();
        $this->assertFalse($res->isSuccess());
        $this->assertEquals('test exception', $res->getMessage());
    }
}
