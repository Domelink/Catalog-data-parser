<?php

namespace App\Repositories;

use App\Models\Category;

final class CategoryRepository
{
    public function firstOrCreate(array $attributes)
    {
        return Category::firstOrCreate($attributes);
    }
}
