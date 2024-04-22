<?php

namespace App\DTO;

use App\Enums\RowKeysType;

final class ProductOffsetDataDto
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

    public static function fromArray(array $row, $type, $category, $additionalInfo): self
    {
        return new self(
            type: ($row[RowKeysType::TYPE->value] = $type),
            name: $row[RowKeysType::CATEGORY_TITLE->value],
            titleId: $category->id,
            manufacturer: $row[RowKeysType::NAME->value],
            description: $row[RowKeysType::PRICE->value],
            price: floatval($row[RowKeysType::GUARANTY->value]),
            guaranty: is_numeric($row[RowKeysType::AVAILABILITY->value]) ? intval($row[RowKeysType::AVAILABILITY->value]) : null,
            availability: ($additionalInfo === [RowKeysType::AVAILABILITY_TRUE->value]),
            codeOfModel: $row[RowKeysType::DESCRIPTION->value],
        );
    }
}
