<?php

namespace App\Repositories;

use App\Models\Product;

final class ProductRepository
{
    public function firstOrCreate(array $data, array $conditions)
    {
        return Product::firstOrCreate($conditions, $data);
    }
}
