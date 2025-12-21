<?php

namespace Database\Seeders\Demo;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const int COUNT = 300_000;

    private const int BUFFER_SIZE = 800;

    public function run(): void
    {
        $progress = $this
            ->command
            ->getOutput()
            ->createProgressBar(
                max: self::COUNT,
            );

        $progress->start();

        $users = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            $users[] = User::factory()
                ->make()
                ->toArray();

            if (count($users) < self::BUFFER_SIZE) {
                continue;
            }

            User::factory()->createMany(
                $users,
            );

            $users = [];

            $progress->advance(
                self::BUFFER_SIZE,
            );
        }

        if (count($users) >= 1) {
            User::factory()
                ->createMany($users);

            $progress->advance(
                count($users),
            );
        }

        $progress->finish();

        $this
            ->command
            ->info('UserSeeder completed.' . "\n");
    }
}
