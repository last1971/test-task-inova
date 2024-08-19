<?php

use App\Models\ExchangeRate;

function getRate(float $from, float $to): string
{
    return number_format($from / $to, 6);
}
