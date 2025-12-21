<?php

namespace App\Http\Resources\Rdbms;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /** @var Pet */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->resource->id,
            'user_id'    => $this->resource->user_id,
            'name'       => $this->resource->name,
            'birth_date' => $this->resource->birth_date,
            'breed_id'   => $this->resource->breed_id,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'breed'      => $this->whenLoaded(
                'breed',
                fn () => BreedResource::make(
                    $this->resource->breed,
                ),
            ),
        ];
    }
}
