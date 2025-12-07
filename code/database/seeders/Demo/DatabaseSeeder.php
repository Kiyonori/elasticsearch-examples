<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
