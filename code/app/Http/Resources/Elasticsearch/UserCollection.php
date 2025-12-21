<?php

namespace App\Http\Resources\Elasticsearch;

use App\Data\ElasticsearchResponses\Users\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class UserCollection extends ResourceCollection
{
    /** @var Collection<int, UserData> */
    public $resource;

    public function __construct(
        $resource,
        private readonly float $responseTime,
        private readonly int $hits_total,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var ?UserData $lastItem */
        $lastItem = $this
            ->resource
            ->last();

        return [
            'response_time' => $this->responseTime,
            'hits_total'    => $this->hits_total,

            'search_after_user_id' => $lastItem
                ?->source
                ?->userId,

            'search_after_pet_id' => $lastItem
                ?->source
                ?->petId,

            'data' => UserResource::collection(
                $this->resource,
            ),
        ];
    }
}
