<?php

namespace Database\Seeders;

use App\ICommands\GetRatesOnDate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $command = GetRatesOnDate::create('01.01.2020');
        $res = $command->execute();
        if (!$res->isSuccess()) {
            throw new \Exception($res->getMessage());
        }
    }
}
