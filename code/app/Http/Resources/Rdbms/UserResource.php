<?php

namespace App\Http\Resources\Rdbms;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @var User */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->resource->id,
            'last_name'         => $this->resource->last_name,
            'last_kana_name'    => $this->resource->last_kana_name,
            'first_name'        => $this->resource->first_name,
            'first_kana_name'   => $this->resource->first_kana_name,
            'email'             => $this->resource->email,
            'email_verified_at' => $this->resource->email_verified_at,
            'password'          => $this->resource->password,
            'remember_token'    => $this->resource->remember_token,
            'prefecture'        => $this->resource->prefecture,
            'city'              => $this->resource->city,
            'street_address'    => $this->resource->street_address,
            'phone_number'      => $this->resource->phone_number,
            'memo'              => $this->resource->memo,

            'pets' => $this->whenLoaded(
                'pets',
                fn () => PetResource::collection(
                    $this->resource->pets,
                ),
            ),

            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
