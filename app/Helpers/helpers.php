<?php

use App\Models\ExchangeRate;

/**
 * Convert function
 * @param float $from
 * @param float $to
 * @return string
 */
function getRate(float $from, float $to): string
{
    return number_format($from / $to, 6);
}
