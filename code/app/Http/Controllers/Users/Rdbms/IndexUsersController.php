<?php

namespace App\Http\Controllers\Users\Rdbms;

use App\Actions\ParseKeywordsAction;
use App\Actions\Rdbms\SearchUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdbms\Users\IndexUsersRequest;
use App\Http\Resources\Rdbms\UserCollection;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Benchmark;

class IndexUsersController extends Controller
{
    /**
     * RDBMS 内の users テーブルを検索
     *
     * @throws Exception
     */
    public function __invoke(
        IndexUsersRequest $request,
    ): JsonResponse {
        $keywords = app(ParseKeywordsAction::class)
            ->handle(
                $request->validated('keywords'),
            );

        $users = Collection::empty();

        /** 検索にかかった時間 */
        $responseTime = Benchmark::measure(
            function () use ($request, $keywords, &$users) {
                $users = app(SearchUserAction::class)
                    ->handle(
                        keywords: $keywords,
                        size: (int) $request->validated('size'),
                        nextCursor: $request->validated('next_cursor'),
                    );
            }
        );

        return response()->json(
            new UserCollection(
                $users,
                $responseTime,
            )
        );
    }
}
