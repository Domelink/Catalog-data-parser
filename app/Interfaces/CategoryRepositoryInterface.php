<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function firstOrCreate(array $attributes);
}
