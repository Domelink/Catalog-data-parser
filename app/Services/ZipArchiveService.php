<?php

namespace App\Services;

use ZipArchive;
use App\Rules\FileNameValidation;
use App\Rules\FileSizeValidation;
use App\Traits\FileValidationTrait;
use App\Interfaces\ZipArchiveServiceInterface;

final readonly class ZipArchiveService implements ZipArchiveServiceInterface
{
    use FileValidationTrait;

    public function processZip(string $zipPath, string $extractPath, FileSizeValidation $fileSizeValidation, FileNameValidation $fileNameValidation): ?string
    {
        $filePath = null;
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $fileExtracted = false;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filename = $stat['name'];
                $fileSize = $stat['size'];

                $fullPath = $extractPath . '/' . $filename;

                if ($this->validateFileSize($fileSize, $fileSizeValidation) && $this->validateFileName($filename, $fileNameValidation)) {
                    $zip->extractTo($extractPath, $filename);
                    $fileExtracted = true;
                    $filePath = $fullPath;
                }
            }

            $zip->close();
            unlink($zipPath);

            return $fileExtracted ? $filePath : null;
        }
        return null;
    }
}
