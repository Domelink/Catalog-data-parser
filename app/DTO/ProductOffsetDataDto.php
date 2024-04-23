<?php

namespace App\DTO;

use App\Enums\RowKeysType;

final class ProductOffsetDataDto extends BaseImportDto
{
    public function __construct(
        public string $type,
        public ?string $name,
        public int $categoryId,
        public string $manufacturer,
        public string $description,
        public float $price,
        public ?int $guaranty,
        public bool $availability,
        public ?string $codeOfModel,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            type: ($params[RowKeysType::TYPE->value] = $params['type']),
            name: $params[RowKeysType::CATEGORY_TITLE->value],
            categoryId: $params['categoryId'],
            manufacturer: $params[RowKeysType::NAME->value],
            description: $params[RowKeysType::PRICE->value],
            price: floatval($params[RowKeysType::GUARANTY->value]),
            guaranty: is_numeric($params[RowKeysType::AVAILABILITY->value]) ? intval($params[RowKeysType::AVAILABILITY->value]) : null,
            availability: ($params['additionalInfo'] === [RowKeysType::AVAILABILITY_TRUE->value]),
            codeOfModel: $params[RowKeysType::DESCRIPTION->value],
        );
    }
}
