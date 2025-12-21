<?php

use App\Models\Pet;
use App\Models\User;
use Database\Seeders\Demo\BreedSeeder;

/**
 * @see \App\Http\Controllers\Users\Rdbms\IndexUsersController
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
    function () {
        $route = route(
            name: 'rdbms.users.index',
            parameters: [
                'keywords' => '山田　太郎',
                'size'     => 10,
            ],
        );

        $response = $this->getJson(
            $route,
        );

        expect($response)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'last_name',
                        'last_kana_name',
                        'first_name',
                        'first_kana_name',
                        'email',
                        'email_verified_at',
                        'password',
                        'remember_token',
                        'prefecture',
                        'city',
                        'street_address',
                        'phone_number',
                        'memo',
                        'pets' => [
                            '*' => [
                                'id',
                                'user_id',
                                'name',
                                'birth_date',
                                'breed_id',
                                'created_at',
                                'updated_at',
                            ],
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'next_cursor',
            ]);
    }
);
