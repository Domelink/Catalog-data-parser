<?php

namespace App\Services;

use App\Models\Product;
use App\DTO\ProductDataDto;
use App\DTO\ProductOffsetDataDto;
use App\Interfaces\ImportServiceInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;

final class ImportService implements ImportServiceInterface
{

    /**
     * Holds a cache of category instances to minimize redundant database queries.
     */
    private array $categoriesCache;

    /**
     * Initializes the service with required repository instances.
     *
     * @param ProductRepositoryInterface $productRepository Repository for handling product data.
     * @param CategoryRepositoryInterface $categoryRepository Repository for handling category data.
     */
    public function __construct(private readonly ProductRepositoryInterface $productRepository, private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getCategory(mixed $categoryKey)
    {
        if (!isset($this->categoriesCache[$categoryKey])) {
            $this->categoriesCache[$categoryKey] = $this->categoryRepository->firstOrCreate(['title' => $categoryKey]);
        }
        return $this->categoriesCache[$categoryKey];
    }

    public function importData(ProductDataDto $dto): Product
    {
        return $this->productRepository->firstOrCreate($dto);
    }

    public function importOffsetData(ProductOffsetDataDto $dto): Product
    {
        return $this->productRepository->firstOrCreate($dto);
    }
}
