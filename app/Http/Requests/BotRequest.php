<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Authorize & validate input data telegram webhook
 */
class BotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->header('x-telegram-bot-api-secret-token') === env('BOT_SECRET_TOKEN', '');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'update_id' => 'required|integer',
            'message' => 'required|array',
            'message.message_id' => 'required|integer',
            'message.from.id' => 'required|integer',
            'message.from.first_name' => 'required|string',
            'message.from.last_name' => 'required|string',
            'message.from.username' => 'required|string',
            'message.from.language_code' => 'required|string',
            'message.from.is_premium' => 'required|bool',
            'message.chat.id' => 'required|integer',
            'message.chat.first_name' => 'required|string',
            'message.chat.last_name' => 'required|string',
            'message.chat.username' => 'required|string',
            'message.chat.type' => 'required|string',
            'message.date' => 'required|integer',
            'message.text' => 'required|string',
        ];
    }
}
