<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class FileValidation implements ValidationRule
{
    protected mixed $maxSize;

    public function __construct($maxSizeInKilobytes)
    {
        $this->maxSize = $maxSizeInKilobytes;
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
        if (str_ends_with($value, '/')) {
            $fail('Invalid file name: Slash.');
            return;
        }

        if (file_exists($value) && filesize($value) > $this->maxSize * 1024) {
            $fail('The file exceeds the maximum allowed size of ' . $this->maxSize . ' KB.');
        }
    }
}
