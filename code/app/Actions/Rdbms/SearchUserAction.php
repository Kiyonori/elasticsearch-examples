<?php

namespace App\Actions\Rdbms;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final readonly class SearchUserAction
{
    /**
     * RDBMS 内の users テーブルを検索
     *
     * @param  array<int, string>  $keywords  例 ['山田', '太郎']
     * @param  int  $size  1ページあたりの件数
     * @return Collection<int, User>
     */
    public function handle(
        array $keywords,
        int $size,
    ): Collection {
        $query = User::query()
            ->join(
                table: 'pets',
                first: 'users.id',
                operator: '=',
                second: 'pets.user_id',
            );

        foreach ($keywords as $keyword) {
            $query->where(
                function ($query) use ($keyword) {
                    $value = '%' . $keyword . '%';

                    $query
                        ->orWhereLike('street_address', $value)
                        ->orWhereLike('city', $value)
                        ->orWhereLike('last_name', $value)
                        ->orWhereLike('first_name', $value)
                        ->orWhereLike('last_kana_name', $value)
                        ->orWhereLike('first_kana_name', $value)
                        ->orWhereLike('pets.name', $value);
                }
            );
        }

        return $query
            ->orderBy('id', 'desc')
            ->limit($size)
            ->get();
    }
}
