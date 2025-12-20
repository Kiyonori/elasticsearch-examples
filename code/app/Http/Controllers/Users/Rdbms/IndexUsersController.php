<?php

namespace App\Http\Controllers\Users\Rdbms;

use App\Actions\ParseKeywordsAction;
use App\Actions\Rdbms\SearchUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\IndexUsersRequest;
use App\Http\Resources\Rdbms\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;

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

        $users = app(SearchUserAction::class)
            ->handle(
                keywords: $keywords,
                size: (int) $request->validated('size'),
            );

        return response()->json(
            UserResource::collection($users)
        );
    }
}
