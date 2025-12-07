<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'sender_user_id'   => User::factory(),
            'receiver_user_id' => User::factory(),
            'message'          => fake('ja_JP')->realText(),
            'read_at'          => fake()->boolean() ? fake()->dateTime() : null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ];
    }
}
