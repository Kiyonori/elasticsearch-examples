<?php

namespace Database\Seeders\Demo;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    private const int COUNT = 3000;

    private const int BUFFER_SIZE = 800;

    public function run(): void
    {
        $userIdMin = User::query()
            ->orderBy('id')
            ->first()
            ->id;

        $userIdMax = User::query()
            ->orderBy('id', 'desc')
            ->first()
            ->id;

        $breedIdMin = Breed::query()
            ->orderBy('id')
            ->first()
            ->id;

        $breedIdMax = Breed::query()
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

        $pets = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            $pets[] = Pet::factory()
                ->state([
                    'user_id' => fake()->numberBetween(
                        $userIdMin,
                        $userIdMax,
                    ),
                    'breed_id' => fake()->numberBetween(
                        $breedIdMin,
                        $breedIdMax,
                    ),
                ])
                ->make()
                ->toArray();

            if (count($pets) < self::BUFFER_SIZE) {
                continue;
            }

            Pet::factory()->createMany(
                $pets,
            );

            $pets = [];

            $progress->advance(
                self::BUFFER_SIZE,
            );
        }

        if (count($pets) >= 1) {
            Pet::factory()->createMany(
                $pets
            );

            $progress->advance(
                count($pets),
            );
        }

        $progress->finish();

        $this
            ->command
            ->info('PetSeeder completed.' . "\n");
    }
}
