<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotRequest;
use App\Jobs\ProcessBotRequest;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function update(BotRequest $botRequest): string
    {
        ProcessBotRequest::dispatch($botRequest->request->all());
        return '';
    }
}
