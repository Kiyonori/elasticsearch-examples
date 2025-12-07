<?php

namespace App\Console\Commands;

use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;
use Kiyonori\ElasticsearchFluentQueryBuilder\DeleteIndex;

class DeleteIndexCommand extends Command
{
    protected $signature = 'elasticsearch:delete-index';

    protected $description = 'Elasticsearch から messages というインデックス削除する';

    /**
     * @throws AuthenticationException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function handle(): void
    {
        try {
            app(DeleteIndex::class)->execute(
                'messages'
            );
        } catch (ClientResponseException $exception) {
            $this->error(
                $exception->getMessage(),
            );

            return;
        }

        $this->info(
            'Elasticsearch から messages というインデックスを削除しました',
        );
    }
}
