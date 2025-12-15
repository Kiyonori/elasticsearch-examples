<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Kiyonori\ElasticsearchFluentQueryBuilder\BulkStoreDocuments;
use stdClass;

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
                        $pets = $user->pets->isNotEmpty()
                            ? $user->pets
                            : [new stdClass];

                        foreach ($pets as $pet) {
                            $buffer[] = [
                                'composite_id' => sprintf(
                                    '%d_%d',
                                    $user->id,
                                    $pet?->id ?? '',
                                ),

                                'user_id'              => $user->id,
                                'user_last_name'       => $user->last_name,
                                'user_last_kana_name'  => $user->last_kana_name,
                                'user_first_name'      => $user->first_name,
                                'user_first_kana_name' => $user->first_kana_name,
                                'user_email'           => $user->email,
                                'user_prefecture'      => $user->prefecture,
                                'user_city'            => $user->city,
                                'user_street_address'  => $user->street_address,
                                'user_phone_number'    => $user->phone_number,
                                'user_memo'            => $user->memo,
                                'user_created_at'      => $user->created_at->toIso8601String(),
                                'user_updated_at'      => $user->updated_at->toIso8601String(),

                                'pet_id'         => ($pet instanceof stdClass) ? null : $pet->id,
                                'pet_name'       => ($pet instanceof stdClass) ? null : $pet->name,
                                'pet_breed'      => ($pet instanceof stdClass) ? null : $pet->breed?->breed,
                                'pet_birth_date' => ($pet instanceof stdClass) ? null : $pet->birth_date?->format('Y-m-d'),
                                'pets_count'     => ($pet instanceof stdClass) ? null : $pets->count(),
                                'pet_created_at' => ($pet instanceof stdClass) ? null : $pet->created_at?->toIso8601String(),
                                'pet_updated_at' => ($pet instanceof stdClass) ? null : $pet->updated_at?->toIso8601String(),
                            ];
                        }
                    }

                    app(BulkStoreDocuments::class)
                        ->execute(
                            indexName: 'users',
                            items: $buffer,
                            idColumnName: 'composite_id',
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
