<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Kiyonori\ElasticsearchFluentQueryBuilder\BulkStoreDocuments;

class IndexAllUsersCommand extends Command
{
    protected $signature = 'elasticsearch:index-all-users';

    protected $description = 'users テーブルの全レコードを Elasticsearch に一括で登録する';

    public function handle(): void
    {
        $totalItems = User::query()
            ->count();

        $progress = $this
            ->getOutput()
            ->createProgressBar(
                max: $totalItems,
            );

        $progress->start();

        User::query()
            ->chunkById(
                count: 100,
                callback: function (Collection $users) use ($progress) {
                    $buffer = [];

                    $users->load('pets.breed');

                    foreach ($users as $user) {
                        /** @var User $user */
                        $buffer = [
                            'id'              => $user->id,
                            'last_name'       => $user->last_name,
                            'last_kana_name'  => $user->last_kana_name,
                            'first_name'      => $user->first_name,
                            'first_kana_name' => $user->first_kana_name,
                            'email'           => $user->email,
                            'prefecture'      => $user->prefecture,
                            'city'            => $user->city,
                            'street_address'  => $user->street_address,
                            'phone_number'    => $user->street_address,
                            'memo'            => $user->memo,
                            'created_at'      => $user->created_at->format('Y-m-d H:i:s'),
                            'updated_at'      => $user->updated_at->format('Y-m-d H:i:s'),
                            'pets'            => (
                                function () use ($user) {
                                    $pets = [];

                                    foreach ($user->pets as $pet) {
                                        $pets[] = [
                                            'id'         => $pet->id,
                                            'name'       => $pet->name,
                                            'birth_date' => $pet->birth_date->format('Y-m-d'),
                                            'breed_id'   => $pet->breed_id,
                                            'created_at' => $pet->created_at->format('Y-m-d H:i:s'),
                                            'updated_at' => $pet->updated_at->format('Y-m-d H:i:s'),
                                            'breed_name' => $pet->breed->name,
                                        ];
                                    }

                                    return $pets;
                                }
                            )(),
                        ];
                    }

                    app(BulkStoreDocuments::class)
                        ->execute(
                            indexName: 'users',
                            items: $buffer,
                            idColumnName: 'id',
                        );

                    $progress->advance(
                        count($buffer),
                    );
                }
            );

        $progress->finish();

        $this->info('users テーブルの全レコードを Elasticsearch に一括で登録しました');
    }
}
