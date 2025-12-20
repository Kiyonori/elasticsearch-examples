<?php

namespace App\Http\Resources\Rdbms;

use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreedResource extends JsonResource
{
    /** @var Breed */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->resource->id,
            'breed'      => $this->resource->breed,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
