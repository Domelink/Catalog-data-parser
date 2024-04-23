<?php

namespace App\DTO;

use App\Enums\RowKeysType;

final class ProductDataDto extends BaseImportDto
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
            name: $params[RowKeysType::NAME->value],
            categoryId: $params['categoryId'],
            manufacturer: $params[RowKeysType::MANUFACTURER->value],
            description: $params[RowKeysType::DESCRIPTION->value],
            price: floatval($params[RowKeysType::PRICE->value]),
            guaranty: isset($params[RowKeysType::GUARANTY->value]) ? intval($params[RowKeysType::GUARANTY->value]) : null,
            availability: ($params[RowKeysType::AVAILABILITY->value]) === [RowKeysType::AVAILABILITY_TRUE->value],
            codeOfModel: $params[RowKeysType::CODE_OF_MODEL->value],
        );
    }
}
