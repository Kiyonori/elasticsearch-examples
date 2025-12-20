<?php

use App\Actions\ParseKeywordsAction;
use App\Actions\Rdbms\SearchUserAction;
use App\Models\Pet;
use App\Models\User;
use Database\Seeders\Demo\BreedSeeder;

/**
 * @see SearchUserAction
 */
beforeEach(
    function () {
        $this->seed([
            BreedSeeder::class,
        ]);

        // user その1
        User::factory()
            ->state([
                'last_name'       => '山田',
                'last_kana_name'  => 'ヤマダ',
                'first_name'      => '太郎',
                'first_kana_name' => 'タロウ',
                'street_address'  => '中町1番地',
            ])
            ->has(
                Pet::Factory()
                    ->state([
                        'name'     => 'チョコ',
                        'breed_id' => 1,
                    ])
            )
            ->create();

        // user その2
        User::factory()
            ->state([
                'last_name'       => '山田',
                'last_kana_name'  => 'ヤマダ',
                'first_name'      => '小太郎',
                'first_kana_name' => 'コタロウ',
                'street_address'  => '中町2番地',
            ])
            ->has(
                Pet::Factory()
                    ->state([
                        'name'     => 'シロ',
                        'breed_id' => 3,
                    ])
            )
            ->create();

        // user その3
        User::factory()
            ->state([
                'last_name'       => '田中',
                'last_kana_name'  => 'タナカ',
                'first_name'      => '花子',
                'first_kana_name' => 'ハナコ',
                'street_address'  => '南町3番地',
            ])
            ->has(
                Pet::Factory()
                    ->state([
                        'name'     => 'ジジ',
                        'breed_id' => 5,
                    ])
            )
            ->create();

        // user その4
        User::factory()
            ->state([
                'last_name'       => '山田',
                'last_kana_name'  => 'ヤマダ',
                'first_name'      => '一太郎',
                'first_kana_name' => 'イチタロウ',
                'street_address'  => '北町1番地',
            ])
            // ℹ️ Pet ナシ
            // ->has(Pet::Factory())
            ->create();
    }
);

test(
    'RDBMS 内の users テーブルを検索',
    /**
     * @throws Exception
     */
    function () {
        $keywords = app(ParseKeywordsAction::class)
            ->handle('山田 太郎');

        $result = app(SearchUserAction::class)
            ->handle($keywords, size: 10);

        expect($result)
            ->toHaveCount(3);
    }
);
