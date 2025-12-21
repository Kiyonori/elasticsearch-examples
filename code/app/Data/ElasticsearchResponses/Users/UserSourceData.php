<?php

namespace App\Data\ElasticsearchResponses\Users;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class UserSourceData extends Data
{
    public function __construct(
        public readonly string $compositeId,
        public readonly int $userId,
        public readonly string $userLastName,
        public readonly string $userLastKanaName,
        public readonly string $userFirstName,
        public readonly string $userFirstKanaName,
        public readonly string $userEmail,
        public readonly string $userPrefecture,
        public readonly string $userCity,
        public readonly string $userStreetAddress,
        public readonly string $userPhoneNumber,
        public readonly string $userMemo,
        public readonly string $userCreatedAt,
        public readonly string $userUpdatedAt,
        public readonly int $petsCount,
        public readonly ?int $petId = null,
        public readonly ?string $petName = null,
        public readonly ?string $petBreed = null,
        public readonly ?string $petBirthDate = null,
        public readonly ?string $petCreatedAt = null,
        public readonly ?string $petUpdatedAt = null,
    ) {}
}
