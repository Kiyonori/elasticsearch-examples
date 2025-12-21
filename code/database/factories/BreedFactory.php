<?php

namespace Database\Factories;

use App\Models\Breed;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreedFactory extends Factory
{
    protected $model = Breed::class;

    public function definition(): array
    {
        return [
            'breed'      => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
