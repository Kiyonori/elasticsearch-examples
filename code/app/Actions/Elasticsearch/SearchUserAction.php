<?php

namespace App\Actions\Elasticsearch;

use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\Body;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\BoolQuery;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\MustQuery;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\ShouldQuery;
use Kiyonori\ElasticsearchFluentQueryBuilder\PrepareElasticsearchClient;

final readonly class SearchUserAction
{
    /**
     * Elasticsearch 内の users インデックスを検索
     *
     * @param  array<int, string>  $keywords  例 ['山田', '太郎']
     * @param  int  $size  1ページあたりの件数
     *
     * @throws ClientResponseException
     * @throws AuthenticationException
     * @throws ServerResponseException
     */
    public function handle(
        array $keywords,
        int $size,
        ?int $searchAfterUserId = null,
        ?int $searchAfterPetId = null,
    ): array {
        $query = Body::query(
            fn (BoolQuery $bool) => $bool(
                function (MustQuery $must) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $must->bool(
                            callback: fn (ShouldQuery $should) => $should
                                ->multiMatch(
                                    query: $keyword,
                                    fields: [
                                        'user_street_address.ngram',
                                        'user_city.ngram',
                                        'user_last_name.ngram',
                                        'user_first_name.ngram',
                                        'user_last_kana_name.ngram',
                                        'user_first_kana_name.ngram',
                                        'pet_name.ngram',
                                    ],
                                ),
                            minimumShouldMatch: 1,
                        );
                    }
                }
            ))
            ->sort(
                fieldName: 'user_id',
                direction: 'desc',
            )
            ->sort(
                fieldName: 'pet_id',
                direction: 'desc',
            )
            ->size($size);

        if ($searchAfterUserId !== null
            && $searchAfterPetId !== null
        ) {
            $query
                ->searchAfter($searchAfterUserId)
                ->searchAfter($searchAfterPetId);
        }

        $client = app(PrepareElasticsearchClient::class)
            ->execute();

        $response = $client->search([
            'index' => 'users',
            'body'  => $query->toArray()['body'],
        ]);

        return json_decode(
            json: $response
                ->getBody()
                ->getContents(),
            associative: true,
        );
    }
}
