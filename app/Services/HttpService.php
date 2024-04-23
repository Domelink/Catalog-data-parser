<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\HttpServiceInterface;

final readonly class HttpService implements HttpServiceInterface
{
    public function downloadFile(string $url, string $path): bool
    {
        $response = Http::get($url);
        if ($response->successful()) {
            file_put_contents($path, $response->body());
            return true;
        }
        return false;
    }
}
