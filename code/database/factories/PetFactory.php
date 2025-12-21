<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'ウミ',
            'キナコ',
            'クロ',
            'ココ',
            'コタロウ',
            'コテツ',
            'コハク',
            'サクラ',
            'ジジ',
            'シロ',
            'ソラ',
            'タマ',
            'チィ',
            'チビ',
            'チャチャ',
            'チャチャマル',
            'チョコ',
            'テン',
            'トラ',
            'ナナ',
            'ハチ',
            'ハナ',
            'ハル',
            'ヒメ',
            'フク',
            'ベル',
            'マル',
            'マロ',
            'マロン',
            'ミー',
            'ミーチャン',
            'ミケ',
            'ミント',
            'ムギ',
            'メイ',
            'モカ',
            'モモ',
            'ユズ',
            'リン',
            'ルイ',
            'ルナ',
            'レオ',
            'レオン',
            '花',
            '空',
            '小春',
            '小太郎',
            '小梅',
            '大福',
            '茶々',
            '茶々丸',
            '麦',
            '姫',
            '福',
            '凛',
            '琥珀',
        ]);

        return [
            'user_id'    => User::factory(),
            'name'       => $name,
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
