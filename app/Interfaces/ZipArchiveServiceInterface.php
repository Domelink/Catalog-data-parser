<?php

namespace App\Interfaces;

use App\Rules\FileNameValidation;
use App\Rules\FileSizeValidation;

interface ZipArchiveServiceInterface
{
    /**
     * Processes a ZIP archive by extracting its files to a specified location after validating them against provided criteria.
     *
     * @param string $zipPath Path to the zip file.
     * @param string $extractPath Path where files should be extracted.
     * @param FileSizeValidation $fileSizeValidation
     * @param FileNameValidation $fileNameValidation
     * @return string|null The path to the last file successfully extracted if any files are extracted, or null if no files are extracted.
     */
    public function processZip(string $zipPath, string $extractPath, FileSizeValidation $fileSizeValidation, FileNameValidation $fileNameValidation): ?string;
}
