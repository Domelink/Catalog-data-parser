<?php

namespace App\Interfaces;

use App\Rules\FileSizeValidation;

interface HttpServiceInterface
{
    /**
     * Download a file from a specified URL and save it to the provided path. Validates the file size
     *
     * @param string $url The URL from which to download the file.
     * @param string $path The local path where the downloaded file should be saved.
     * @param FileSizeValidation $fileSizeValidation The validation rule to apply to check file size.
     * @return bool Returns true if the file is successfully downloaded and saved, false otherwise.
     */
    public function downloadFile(string $url, string $path, FileSizeValidation $fileSizeValidation): bool;
}
