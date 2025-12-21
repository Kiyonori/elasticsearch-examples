<?php

namespace App\Http\Resources\Elasticsearch;

use App\Data\ElasticsearchResponses\Users\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /** @var array<int, UserData> */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'data' => UserResource::collection(
                $this->resource,
            ),
        ];
    }
}
