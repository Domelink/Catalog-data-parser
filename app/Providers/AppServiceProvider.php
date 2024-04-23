<?php

namespace App\Providers;

use App\Services\HttpService;
use App\Services\ImportService;
use App\Services\ZipArchiveService;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Interfaces\HttpServiceInterface;
use App\Interfaces\ImportServiceInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\ZipArchiveServiceInterface;
use App\Interfaces\CategoryRepositoryInterface;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImportService::class, function ($app) {
            return new ImportService(
                $app->make(ProductRepository::class),
                $app->make(CategoryRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(HttpServiceInterface::class, HttpService::class);
        $this->app->bind(ImportServiceInterface::class, ImportService::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ZipArchiveServiceInterface::class, ZipArchiveService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
