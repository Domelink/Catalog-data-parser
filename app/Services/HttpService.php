<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

final readonly class HttpService
{
    /**
     * Download a file from a URL and save it to a specified path.
     *
     * @param string $url URL to download the file from.
     * @param string $path Path to save the downloaded file.
     * @return bool Returns true if download and save were successful, false otherwise.
     */
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
