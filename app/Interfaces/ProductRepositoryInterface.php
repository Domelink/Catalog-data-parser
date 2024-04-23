<?php

namespace App\Interfaces;

use App\DTO\BaseImportDto;

interface ProductRepositoryInterface
{
    public function firstOrCreate(BaseImportDto $dto);
}
