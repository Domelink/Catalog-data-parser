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
                $stat = $zip->statIndex($i);
                $filename = $stat['name'];
                $fileSize = $stat['size'];

                if ($this->validateFile($filename, $fileSize, $fileValidation)) {
                    $zip->extractTo($extractPath, $filename);
                }

                $filePath = $extractPath . '/' . $filename;
            }
            $zip->close();
            unlink($zipPath);
        }
        return $filePath;
    }

    /**
     * Validates the file using the provided validation rules.
     *
     * @param FileValidation $fileValidation Validation rules to apply.
     * @return bool True if validation passes, false otherwise.
     */
    private function validateFile($filename, $fileSize, FileValidation $fileValidation): bool
    {
        $validationPassed = true;
        $fileValidation->validate($filename, $fileSize, function ($message) use (&$validationPassed) {
            logger($message);
            $validationPassed = false;
        });
        return $validationPassed;
    }
}
