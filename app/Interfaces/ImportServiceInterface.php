<?php

namespace App\Interfaces;

use App\Models\Product;
use App\DTO\ProductDataDto;
use App\DTO\ProductOffsetDataDto;

interface ImportServiceInterface
{
    /**
     * Retrieves a category from the cache or database if not available.
     *
     * @param mixed $categoryKey The key used to identify the category.
     */
    public function getCategory(mixed $categoryKey);

    /**
     * Imports data using the provided data transfer object (DTO).
     *
     * @param ProductDataDto $dto The data transfer object containing product data.
     * @return Product The newly created or updated product model instance.
     */
    public function importData(ProductDataDto $dto): Product;

    /**
     * Imports product data with an offset using the provided DTO.
     * This method is specifically used for processing differently structured data.
     *
     * @param ProductOffsetDataDto $dto The data transfer object containing offset product data.
     * @return Product The newly created or updated product model instance.
     */
    public function importOffsetData(ProductOffsetDataDto $dto): Product;
}
