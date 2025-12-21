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
use Illuminate\Support\Benchmark;

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

        $elasticsearchResponse = null;

        /** 検索にかかった時間 */
        $responseTime = Benchmark::measure(
            function () use ($request, $keywords, &$elasticsearchResponse) {
                $elasticsearchResponse = app(SearchUserAction::class)
                    ->handle(
                        keywords: $keywords,
                        size: (int) $request->validated('size'),

                        searchAfterUserId: $request->filled('search_after_user_id')
                            ? (int) $request->validated('search_after_user_id')
                            : null,

                        searchAfterPetId: $request->filled('search_after_pet_id')
                            ? (int) $request->validated('search_after_pet_id')
                            : null,
                    );
            }
        );

        $users = UserData::collect(
            Arr::get(
                $elasticsearchResponse,
                'hits.hits',
            ),
        );

        return response()->json(
            new UserCollection(
                $users,
                $responseTime,
                hits_total: Arr::get(
                    array: $elasticsearchResponse,
                    key: 'hits.total.value',
                    default: 0,
                ),
            ),
        );
    }
}
