<?php

namespace App\DTO;

abstract class BaseImportDto extends BaseDto
{
    public function prepareProductData(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'manufacturer' => $this->manufacturer,
            'description' => $this->description,
            'price' => $this->price,
            'guaranty' => $this->guaranty,
            'availability' => $this->availability,
        ];
    }
}
