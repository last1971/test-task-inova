<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotRequest;
use App\Jobs\ProcessCreateTelegramMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * @param BotRequest $botRequest
     * @return JsonResponse
     */
    public function update(BotRequest $botRequest): JsonResponse
    {
        ProcessCreateTelegramMessage::dispatch($botRequest->request->all());
        return response()->json(['ok' => true], 200);
    }
}
