<?php

namespace App\Actions;

use Carbon\CarbonPeriodImmutable;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\Body;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\BoolQuery;
use Kiyonori\ElasticsearchFluentQueryBuilder\Builders\MustQuery;

final readonly class SearchMessageByElasticsearchAction
{
    /**
     * メッセージ検索 (Elasticsearch 版)
     *
     * @param  string  $messageKeyword  検索対象のキーワード
     * @param  CarbonPeriodImmutable  $dateRange  日付時刻の範囲
     * @param  int  $senderUserId  送信者のユーザ ID
     * @param  int  $receiverUserId  受信者のユーザ ID
     */
    public function handle(
        string $messageKeyword,
        CarbonPeriodImmutable $dateRange,
        int $senderUserId,
        int $receiverUserId,
    ) {
        $result = Body::query(
            function (BoolQuery $bool) use ($dateRange, $receiverUserId, $senderUserId, $messageKeyword) {
                $bool(
                    function (MustQuery $must) use ($dateRange, $receiverUserId, $senderUserId, $messageKeyword) {
                        $must
                            ->match('message', $messageKeyword)
                            ->term('sender_user_id', $senderUserId)
                            ->term('receiver_user_id', $receiverUserId)
                            ->range(
                                fieldName: 'created_at',
                                gte: $dateRange->start->format('Y-m-d H:i:s'),
                                lte: $dateRange->end->format('Y-m-d H:i:s'),
                            );
                    }
                );
            }
        )->toArray();
    }
}
