<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramMessage extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'telegram_chat_id', 'telegram_user_id', 'text', 'date'];

    /**
     * @return BelongsTo
     */
    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class);
    }

    /**
     * @return BelongsTo
     */
    public function telegramChat(): BelongsTo
    {
        return $this->belongsTo(TelegramChat::class);
    }

    /**
     * @return BelongsTo
     */
    public function telegramNextCommand(): BelongsTo
    {
        return $this->belongsTo(
            TelegramNextCommand::class,
            'telegram_user_id',
            'telegram_user_id'
        )->where('telegram_chat_id', $this->telegram_chat_id);
    }
}
