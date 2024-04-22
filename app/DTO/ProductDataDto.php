<?php

namespace App\DTO;

use App\Enums\RowKeysType;

final class ProductDataDto
{
    public function __construct(
        public string $type,
        public ?string $name,
        public int $titleId,
        public string $manufacturer,
        public string $description,
        public float $price,
        public ?int $guaranty,
        public bool $availability,
        public ?string $codeOfModel,
    ) {}

    public static function fromArray(array $row, $type, $category): self
    {
        return new self(
            type: ($row[RowKeysType::TYPE->value] = $type),
            name: $row[RowKeysType::NAME->value],
            titleId: $category->id,
            manufacturer: $row[RowKeysType::MANUFACTURER->value],
            description: $row[RowKeysType::DESCRIPTION->value],
            price: floatval($row[RowKeysType::PRICE->value]),
            guaranty: isset($row[RowKeysType::GUARANTY->value]) ? intval($row[RowKeysType::GUARANTY->value]) : null,
            availability: ($row[RowKeysType::AVAILABILITY->value]) === [RowKeysType::AVAILABILITY_TRUE->value],
            codeOfModel: $row[RowKeysType::CODE_OF_MODEL->value],
        );
    }
}
