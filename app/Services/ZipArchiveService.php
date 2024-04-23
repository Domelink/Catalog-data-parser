<?php

namespace App\Services;

use ZipArchive;
use App\Rules\FileValidation;
use App\Interfaces\ZipArchiveServiceInterface;

final readonly class ZipArchiveService implements ZipArchiveServiceInterface
{
    public function processZip(string $zipPath, string $extractPath, FileValidation $fileValidation): string
    {
        $filePath = '';
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $filePath = $extractPath . '/' . $filename;

                if (str_ends_with($filename, '/')) {
                    logger("Skipping directory: {$filename}");
                    continue;
                }

                $zip->extractTo($extractPath, $filename);
                $this->validateFile($filePath, $fileValidation);
            }
            $zip->close();
            unlink($zipPath);
        }
        return $filePath;
    }

    /**
     * Validates the file using the provided validation rules.
     *
     * @param string $filePath Path to the file to validate.
     * @param FileValidation $fileValidation Validation rules to apply.
     * @return bool True if validation passes, false otherwise.
     */
    private function validateFile(string $filePath, FileValidation $fileValidation): void
    {
        logger($filePath);
        $fileValidation->validate('file', $filePath, function ($message) use ($filePath) {
            logger($message);
            unlink($filePath);
        });
    }
}
