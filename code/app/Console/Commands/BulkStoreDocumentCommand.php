<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Kiyonori\ElasticsearchFluentQueryBuilder\BulkStoreDocuments;

class BulkStoreDocumentCommand extends Command
{
    protected $signature = 'elasticsearch:bulk-store-document';

    protected $description = 'messages テーブルの全レコードを Elasticsearch に一括で登録する';

    public function handle(): void
    {
        $totalItems = Message::query()
            ->count();

        $progress = $this
            ->getOutput()
            ->createProgressBar(
                max: $totalItems,
            );

        $progress->start();

        Message::query()
            ->chunkById(
                count: 100,
                callback: function (Collection $messages) use ($progress) {
                    $buffer = [];

                    foreach ($messages as $message) {
                        /** @var Message $message */
                        $buffer[] = [
                            'id'               => $message->id,
                            'sender_user_id'   => $message->sender_user_id,
                            'receiver_user_id' => $message->receiver_user_id,
                            'message'          => $message->message,
                            'read_at'          => $message->read_at,
                            'created_at'       => $message->created_at,
                            'updated_at'       => $message->updated_at,
                            'deleted_at'       => $message->deleted_at,
                        ];
                    }

                    app(BulkStoreDocuments::class)
                        ->execute(
                            indexName: 'messages',
                            items: $buffer,
                            idColumnName: 'id',
                        );

                    $progress->advance(
                        count($buffer),
                    );
                }
            );

        $progress->finish();

        $this->info('messages テーブルの全レコードを Elasticsearch に一括で登録しました');
    }
}
