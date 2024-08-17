<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramUser extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'first_name', 'last_name', 'username', 'language_code', 'is_premium'];

    public function telegramMessages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class);
    }
}
