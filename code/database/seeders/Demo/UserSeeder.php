<?php

namespace Database\Seeders\Demo;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const int COUNT = 100;

    public function run(): void
    {
        User::factory()
            ->count(self::COUNT)
            ->create();
    }
}
