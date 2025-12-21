<?php

namespace App\Data\ElasticsearchResponses\Users;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        #[MapInputName('_index')]
        public readonly string $index,
        #[MapInputName('_id')]
        public readonly string $id,
        #[MapInputName('_score')]
        public readonly mixed $score,
        #[MapInputName('_source')]
        public readonly UserSourceData $source,
    ) {}
}
