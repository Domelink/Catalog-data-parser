<?php

namespace App\Services;

use App\Rules\FileSizeValidation;
use App\Traits\FileValidationTrait;
use Illuminate\Support\Facades\Http;
use App\Interfaces\HttpServiceInterface;

final readonly class HttpService implements HttpServiceInterface
{
    use FileValidationTrait;

    public function downloadFile(string $url, string $path, FileSizeValidation $fileSizeValidation): bool
    {
        $response = Http::get($url);
        $fileSize = $response->header('Content-Length');
        if ($this->validateFileSize($fileSize, $fileSizeValidation) && $response->successful()) {
            file_put_contents($path, $response->body());
            return true;
        }
        return false;
    }
}
