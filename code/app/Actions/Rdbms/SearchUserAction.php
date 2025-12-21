<?php

namespace App\Actions\Rdbms;

use App\Models\User;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

final readonly class SearchUserAction
{
    /**
     * RDBMS 内の users テーブルを検索
     *
     * @param  array<int, string>  $keywords  例 ['山田', '太郎']
     * @param  int  $size  1ページあたりの件数
     */
    public function handle(
        array $keywords,
        int $size,
        ?string $nextCursor = null,
    ): CursorPaginator {
        $query = User::query();

        foreach ($keywords as $keyword) {
            $query->where(
                function (Builder $query) use ($keyword) {
                    $value = '%' . $keyword . '%';

                    $query
                        ->orWhereLike('street_address', $value)
                        ->orWhereLike('city', $value)
                        ->orWhereLike('last_name', $value)
                        ->orWhereLike('first_name', $value)
                        ->orWhereLike('last_kana_name', $value)
                        ->orWhereLike('first_kana_name', $value)
                        ->orWhereHas(
                            'pets',
                            fn (Builder $query) => $query
                                ->whereLike('name', $value),
                        );
                }
            );
        }

        return $query
            ->orderBy('id', 'desc')
            ->with([
                'pets' => fn (HasMany $query) => $query
                    ->orderBy('id', 'desc'),
            ])
            ->cursorPaginate(
                $size,
                cursor: $nextCursor,
            );
    }
}
