<?php

namespace App\Http\Resources\Rdbms;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /** @var CursorPaginator */
    public $resource;

    public function __construct(
        $resource,
        private readonly float $responseTime,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'response_time' => $this->responseTime,

            'next_cursor' => $this
                ->resource
                ->nextCursor()
                ?->encode(),

            'data' => UserResource::collection(
                $this->resource->items(),
            ),
        ];
    }
}
