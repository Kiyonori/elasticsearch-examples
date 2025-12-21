<?php

namespace App\Http\Controllers\Users\Elasticsearch;

use App\Actions\Elasticsearch\SearchUserAction;
use App\Actions\ParseKeywordsAction;
use App\Data\ElasticsearchResponses\Users\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Elasticsearch\Users\IndexUsersRequest;
use App\Http\Resources\Elasticsearch\UserCollection;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

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

        $elasticsearchResponse = app(SearchUserAction::class)
            ->handle(
                keywords: $keywords,
                size: (int) $request->validated('size'),
            );

        $users = UserData::collect(
            Arr::get(
                $elasticsearchResponse,
                'hits.hits'
            ),
        );

        return response()->json(
            UserCollection::make(
                $users,
            ),
        );
    }
}
