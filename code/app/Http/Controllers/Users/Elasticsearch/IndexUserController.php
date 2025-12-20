<?php

namespace App\Http\Controllers\Users\Elasticsearch;

use App\Actions\Elasticsearch\SearchUserAction;
use App\Actions\ParseKeywordsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\IndexUsersRequest;
use App\Http\Resources\Elasticsearch\UserCollection;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Http\JsonResponse;

class IndexUserController extends Controller
{
    /**
     * Elasticsearch 内の users インデックスを検索
     *
     * @throws AuthenticationException
     * @throws ServerResponseException
     * @throws ClientResponseException
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
            UserCollection::make(
                $users,
            ),
        );
    }
}
