<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = ['currency_id', 'rate', 'date'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
