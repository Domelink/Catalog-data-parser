<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final readonly class FileValidation implements ValidationRule
{
    public function __construct(private int $maxSize)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $value);
        finfo_close($fileInfo);

        if ($mimeType !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $fail('The file must be a valid .xlsx file.');
        }

        if (file_exists($value) && filesize($value) > $this->maxSize * 1024) {
            $fail('The file exceeds the maximum allowed size of ' . $this->maxSize . ' KB.');
        }
    }
}
