<?php

namespace App\Repositories;

use App\Models\Product;
use App\DTO\BaseImportDto;
use App\Interfaces\ProductRepositoryInterface;

final class ProductRepository implements ProductRepositoryInterface
{
    public function firstOrCreate(BaseImportDto $dto)
    {
        return Product::firstOrCreate(
            ['code_of_model' => $dto->codeOfModel],
            $dto->prepareProductData(),
        );
    }
}
