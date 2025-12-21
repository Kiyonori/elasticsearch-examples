<?php

namespace Database\Seeders\Demo;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    private const int COUNT = 10_000;

    private const int BUFFER_SIZE = 800;

    public function run(): void
    {
        $minUserId = User::query()
            ->orderBy('id')
            ->first()
            ->id;

        $maxUserId = User::query()
            ->orderBy('id', 'desc')
            ->first()
            ->id;

        $progress = $this
            ->command
            ->getOutput()
            ->createProgressBar(
                max: self::COUNT,
            );

        $progress->start();

        $messages = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            $messages[] = Message::factory()
                ->state([
                    'sender_user_id'   => fake()->numberBetween($minUserId, $maxUserId),
                    'receiver_user_id' => fake()->numberBetween($minUserId, $maxUserId),
                ])
                ->make()
                ->toArray();

            $progress->advance();

            if (count($messages) >= self::BUFFER_SIZE) {
                Message::factory()->createMany($messages);
                $messages = [];
            }
        }

        if (count($messages) >= 1) {
            Message::factory()
                ->createMany($messages);
        }

        $progress->finish();
        $this->command->info("\nMessageSeeder completed.");
    }
}
