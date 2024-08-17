<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramChat extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'first_name', 'last_name', 'username', 'type'];

    public function telegramMessages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class);
    }

    public function telegramNexCommands(): HasOne
    {
        return $this->hasOne(TelegramNextCommand::class);
    }
}
