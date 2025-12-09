<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'user_id'    => $this->faker->randomNumber(),
            'name'       => $this->faker->name(),
            'birth_date' => fake()->dateTimeBetween(
                startDate: today()->subYears(25),
                endDate: today()->subMonths(6),
            ),
            'breed_id'   => Breed::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
