<?php

namespace App\Http\Resources\Rdbms;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /** @var CursorPaginator */
    public $resource;
    
    public function toArray(Request $request): array
    {
        return [
            'data' => UserResource::collection(
                $this->resource->items(),
            ),
            'next_cursor' => $this
                ->resource
                ->nextCursor()
                ?->encode()
        ];
    }
}
