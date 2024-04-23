<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function firstOrCreate(array $attributes)
    {
        return Category::firstOrCreate($attributes);
    }
}
