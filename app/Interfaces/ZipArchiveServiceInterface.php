<?php

namespace App\Interfaces;

use App\Rules\FileValidation;

interface ZipArchiveServiceInterface
{
    /**
     * Process the zip file: extract files, validate, and dispatch jobs.
     *
     * @param string $zipPath Path to the zip file.
     * @param string $extractPath Path where files should be extracted.
     * @param FileValidation $fileValidation Validation rules for files.
     * @return string $filePath
     */
    public function processZip(string $zipPath, string $extractPath, FileValidation $fileValidation): string;
}
