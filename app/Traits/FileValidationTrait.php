<?php

namespace App\Traits;

use App\Rules\FileNameValidation;
use App\Rules\FileSizeValidation;

trait FileValidationTrait
{
    /**
     * Validates the file size against specified limits using the provided FileSizeValidation object.
     *
     * @param int $fileSize The size of the file to validate.
     * @param FileSizeValidation $fileSizeValidation Validation rules to apply.
     * @return bool True if validation passes, false otherwise.
     */
    private function validateFileSize(int $fileSize, FileSizeValidation $fileSizeValidation): bool
    {
        $validationPassed = true;

        $fileSizeValidation->validate('fileSize', $fileSize, function ($message) use (&$validationPassed) {
            logger($message);
            $validationPassed = false;
        });

        return $validationPassed;
    }

    /**
     * Validates the file name to ensure it meets specific criteria, such as the correct file extension.
     *
     * @param string $filename The name of the file to validate.
     * @param FileNameValidation $fileNameValidation Validation rules to apply.
     * @return bool True if validation passes, false otherwise.
     */
    public function validateFileName(string $filename, FileNameValidation $fileNameValidation): bool
    {
        $validationPassed = true;
        $fileNameValidation->validate($filename, 'filename', function ($message) use (&$validationPassed) {
            logger($message);
            $validationPassed = false;
        });

        return $validationPassed;
    }
}

