<?php

namespace App\Services;

use App\Models\Product;
use App\DTO\ProductDataDto;
use App\DTO\ProductOffsetDataDto;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

final class ImportService
{

    /**
     * Holds a cache of category instances to minimize redundant database queries.
     */
    private array $categoriesCache;

    /**
     * Initializes the service with required repository instances.
     *
     * @param ProductRepository $productRepository Repository for handling product data.
     * @param CategoryRepository $categoryRepository Repository for handling category data.
     */
    public function __construct(private readonly ProductRepository $productRepository, private readonly CategoryRepository $categoryRepository)
    {
    }

    /**
     * Retrieves a category from the cache or database if not available.
     *
     * @param mixed $categoryKey The key used to identify the category.
     */
    public function getCategory(mixed $categoryKey)
    {
        if (!isset($this->categoriesCache[$categoryKey])) {
            $this->categoriesCache[$categoryKey] = $this->categoryRepository->firstOrCreate(['title' => $categoryKey]);
        }
        return $this->categoriesCache[$categoryKey];
    }

    /**
     * Imports data using the provided data transfer object (DTO).
     *
     * @param ProductDataDto $dto The data transfer object containing product data.
     * @return Product The newly created or updated product model instance.
     */
    public function importData(ProductDataDto $dto): Product
    {
        $data = $this->prepareProductData($dto);
        return $this->productRepository->firstOrCreate($data, ['code_of_model' => $dto->codeOfModel]);
    }

    /**
     * Imports product data with an offset using the provided DTO.
     * This method is specifically used for processing differently structured data.
     *
     * @param ProductOffsetDataDto $dto The data transfer object containing offset product data.
     * @return Product The newly created or updated product model instance.
     */
    public function importOffsetData(ProductOffsetDataDto $dto): Product
    {
        $data = $this->prepareProductData($dto);
        return $this->productRepository->firstOrCreate($data, ['code_of_model' => $dto->codeOfModel]);
    }

    /**
     * Prepares the product data for database insertion based on the provided DTO.
     *
     * @param ProductDataDto|ProductOffsetDataDto $dto The data transfer object containing product data.
     * @return array An associative array representing the product's attributes.
     */
    private function prepareProductData(ProductDataDto|ProductOffsetDataDto $dto): array
    {
        return [
            'type' => $dto->type,
            'name' => $dto->name,
            'title_id' => $dto->titleId,
            'manufacturer' => $dto->manufacturer,
            'description' => $dto->description,
            'price' => $dto->price,
            'guaranty' => $dto->guaranty,
            'availability' => $dto->availability,
        ];
    }
}
