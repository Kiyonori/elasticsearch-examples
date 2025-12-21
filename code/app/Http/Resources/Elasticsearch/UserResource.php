<?php

namespace App\Http\Resources\Elasticsearch;

use App\Data\ElasticsearchResponses\Users\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @var UserData */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            '_index'  => $this->resource->index,
            '_id'     => $this->resource->id,
            '_score'  => $this->resource->score,
            '_source' => [
                'composite_id'         => $this->resource->source->compositeId,
                'user_id'              => $this->resource->source->userId,
                'user_last_name'       => $this->resource->source->userLastName,
                'user_last_kana_name'  => $this->resource->source->userLastKanaName,
                'user_first_name'      => $this->resource->source->userFirstName,
                'user_first_kana_name' => $this->resource->source->userFirstKanaName,
                'user_email'           => $this->resource->source->userEmail,
                'user_prefecture'      => $this->resource->source->userPrefecture,
                'user_city'            => $this->resource->source->userCity,
                'user_street_address'  => $this->resource->source->userStreetAddress,
                'user_phone_number'    => $this->resource->source->userPhoneNumber,
                'user_memo'            => $this->resource->source->userMemo,
                'user_created_at'      => $this->resource->source->userCreatedAt,
                'user_updated_at'      => $this->resource->source->userUpdatedAt,
                'pet_id'               => $this->resource->source->petId,
                'pet_name'             => $this->resource->source->petName,
                'pet_breed'            => $this->resource->source->petBreed,
                'pet_birth_date'       => $this->resource->source->petBirthDate,
                'pets_count'           => $this->resource->source->petsCount,
                'pet_created_at'       => $this->resource->source->petCreatedAt,
                'pet_updated_at'       => $this->resource->source->petUpdatedAt,
            ],
        ];
    }
}
