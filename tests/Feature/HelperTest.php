<?php

namespace Tests\Feature;

use App\Models\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testGetRate(): void
    {
        $this->assertEquals('0.500000', getRate(1, 2));
    }
}
