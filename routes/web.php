<?php

use App\Http\Controllers\BotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'ok';
});

// Telegram webhook route
Route::post('/{token}', [BotController::class, 'update'])->where('token', env('BOT_PATH', ''));
