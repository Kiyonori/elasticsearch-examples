<?php

use App\Models\Pet;
use App\Models\User;
use Database\Seeders\Demo\BreedSeeder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Support\Facades\Artisan;
use Kiyonori\ElasticsearchFluentQueryBuilder\ApplyMapping;
use Kiyonori\ElasticsearchFluentQueryBuilder\DeleteIndex;
use Kiyonori\ElasticsearchFluentQueryBuilder\PrepareElasticsearchClient;

/**
 * @see \App\Http\Controllers\Users\Elasticsearch\IndexUserController
 */
beforeEach(
    /**
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
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

        // テスト開始前にインデックスを削除する
        app(DeleteIndex::class)
            ->execute(
                indexName: 'users',
                suppressNotFoundException: true,
            );

        // users というインデックスを作成する
        app(ApplyMapping::class)->execute(
            jsonFilePath: storage_path('elasticsearch/explicit-mappings/users.json')
        );

        Artisan::call(
            'elasticsearch:index-all-users'
        );

        $client = app(PrepareElasticsearchClient::class)
            ->execute();

        $client
            ->indices()
            ->refresh([
                'index' => 'users',
            ]);
    }
);

test(
    'Elasticsearch 内の users インデックスを検索',
    function () {
        $route = route(
            name: 'elasticsearch.users.index',
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
            ->assertJsonCount(
                count: 3,
                key: 'data',
            )
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        '_index',
                        '_id',
                        '_score',
                        '_source' => [
                            'composite_id',
                            'user_id',
                            'user_last_name',
                            'user_last_kana_name',
                            'user_first_name',
                            'user_first_kana_name',
                            'user_email',
                            'user_prefecture',
                            'user_city',
                            'user_street_address',
                            'user_phone_number',
                            'user_memo',
                            'user_created_at',
                            'user_updated_at',
                            'pet_id',
                            'pet_name',
                            'pet_breed',
                            'pet_birth_date',
                            'pets_count',
                            'pet_created_at',
                            'pet_updated_at',
                        ],
                    ],
                ],
                'search_after_user_id',
                'search_after_pet_id',
            ]);
    }
);
