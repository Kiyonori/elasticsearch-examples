<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kiyonori\ElasticsearchFluentQueryBuilder\ApplyMapping;

class ExplicitMappingCommand extends Command
{
    protected $signature = 'elasticsearch:explicit-mapping';

    protected $description = 'Elasticsearch への明示的なマッピング';

    public function handle(): void
    {
        $newIndexName = app(ApplyMapping::class)->execute(
            jsonFilePath: storage_path('elasticsearch/explicit-mappings/messages.json'),
        );

        $this
            ->output
            ->writeln(
                sprintf(
                    '明示的なマッピング %s を登録しました',
                    $newIndexName,
                )
            );
    }
}
